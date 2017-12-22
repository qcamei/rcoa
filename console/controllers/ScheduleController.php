<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace console\controllers;

use common\models\Config;
use common\models\mconline\McbsActivityFile;
use common\models\ScheduledTaskLog;
use Exception;
use wskeee\notification\NotificationManager;
use wskeee\rbac\RbacManager;
use wskeee\webuploader\models\Uploadfile;
use Yii;
use yii\console\Controller;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Description of MconlineController
 *
 * @author Administrator
 */
class ScheduleController extends Controller {

    /**
     * 日常计划
     * 
     * 定时每天凌晨执行
     */
    public function actionEveryDay() {
        //清理过期文件
        $this->clearExpireFile();
        //检查存储上限
        $this->checkMaxFileSize();
    }

    /**
     * 删除到期文件
     * 
     * 1、查出所有过期的关联（活动与文件关联），添加到删除列表
     * 2、从过期的关联中检查看有没有其它未过期的关联，有即移出删除列表
     * 3、删除物理文件
     * 4、在文件表中标记文件已删除
     * 5、添加删除操作记录
     */
    private function clearExpireFile() {

        $taskLog = new ScheduledTaskLog();
        $taskLog->type = ScheduledTaskLog::TYPE_MCONLINE_CHECK_EXPIRE_FILE;
        $taskLog->action = $this->route;

        try {
            $mc_root = \Yii::getAlias('@mconline') . '/web/';
            /**
             * 1、查出所有过期的关联（活动与文件关联），添加到删除列表
             */
            $expireFiles = (new Query())
                    ->select(['ActivityFile.file_id', 'File.name AS file_name', 'File.path AS file_path', 'File.size AS file_size'])
                    ->from(['ActivityFile' => McbsActivityFile::tableName()])
                    ->leftJoin(['File' => Uploadfile::tableName()], 'ActivityFile.file_id = File.id')
                    ->where(['<', 'ActivityFile.expire_time', time()])
                    ->andWhere(['File.is_del' => 0])
                    ->groupBy('ActivityFile.file_id')
                    ->all();
            /**
             * 2、从过期的关联中检查看有没有其它未过期的关联，有即移出删除列表
             */
            $unExpireFiles = (new Query())
                    ->select(['ActivityFile.file_id',])
                    ->from(['ActivityFile' => McbsActivityFile::tableName()])
                    ->where(['>', 'ActivityFile.expire_time', time()])
                    ->andWhere(['in', 'ActivityFile.file_id', array_unique(ArrayHelper::getColumn($expireFiles, 'file_id'))])
                    ->all();
            $unExpireFiles = ArrayHelper::index($unExpireFiles, 'file_id');
            /**
             * 3、删除物理文件
             */
            $exec_result = [];
            $file_results = [];             //{file_id,file_path,result,mes}
            $all_size = 0;                  //全部大小
            $fail_num = 0;                  //失败数
            $success_num = 0;               //成功数
            $delFileIds = [];               //已删除文件的ID

            foreach ($expireFiles as $expireFile) {
                if (!isset($unExpireFiles[$expireFile['file_id']])) {
                    //设置已经检查过
                    $unExpireFiles[$expireFile['file_id']] = true;
                    //创建一条删除记录
                    $result = [
                        'file_id' => $expireFile['file_id'],
                        'file_name' => $expireFile['file_name'],
                        'file_path' => $expireFile['file_path'],
                        'file_size' => $expireFile['file_size'],
                    ];
                    if (!file_exists($mc_root . $expireFile['file_path'])) {
                        $result['result'] = 0;
                        $result['mes'] = '文件不存在！';
                        $fail_num++;
                    }
                    if (is_file($mc_root . $expireFile['file_path']) || is_link($mc_root . $expireFile['file_path'])) {
                        try {
                            //删除物理文件
                            unlink($expireFile['file_path']);
                            $result['result'] = 1;
                            $result['mes'] = '';
                            $all_size += $expireFile['file_size'];
                            $success_num ++;
                            $delFileIds [] = $expireFile['file_id'];
                        } catch (Exception $ex) {
                            $result['result'] = 0;
                            $result['mes'] = $ex->getMessage();
                            $fail_num++;
                        }
                    }
                    $file_results [] = $result;
                }
            }

            //组装执行结果
            $exec_result['file_results'] = $file_results;
            $exec_result['all_size'] = $all_size;
            $exec_result['fail_num'] = $fail_num;
            $exec_result['success_num'] = $success_num;
            $exec_result['mark_del_result'] = 1;

            /**
             * 4、在文件表中标记文件已删除
             */
            try {
                Yii::$app->db->createCommand()->update(Uploadfile::tableName(), ['is_del' => 1], ['in', 'id', $delFileIds])->execute();
            } catch (Exception $ex) {
                $exec_result['mark_del_result'] = 0;
                $exec_result['mark_del_mes'] = $ex->getMessage();
            }

            $taskLog->result = 1;
            $taskLog->feedback = json_encode($exec_result);
        } catch (Exception $ex) {
            $taskLog->result = 0;
            $taskLog->feedback = $ex->getMessage() . "\n" . $ex->getTraceAsString();
        }
        /*
         * 5、添加删除操作记录
         */
        $taskLog->save();
    }

    /**
     * 检查所有文件所占的空间大小，如果超出设置的大小向管理员发出警告
     * 
     * 1、查出所有文件大小
     * 2、检查是否超出设定的警告值
     * 3、查出所有管理员，并发送警告信息
     * 4、添加检查记录
     * 
     */
    private function checkMaxFileSize() {
        //记录
        $taskLog = new ScheduledTaskLog();
        $taskLog->type = ScheduledTaskLog::TYPE_CHECK_MAX_FILE_LIMIT;
        $taskLog->action = $this->route;
        try {
            /**
             * 1、查出相关配置值
             * 
             * max_filesize_warning        当所有文件大小超过该值即向管理员发出警告(250GB=250*1024*1024*1024)
             * max_filesize_limit          设置文件上传总大小限制(300GB=300*1024*1024*1024)
             */
            $configs = (new Query())
                    ->select(['config_name', 'config_value'])
                    ->from(['Config' => Config::tableName()])
                    ->where(['in', 'config_name', ['max_filesize_warning', 'max_filesize_limit']])
                    ->all();
            $configs = ArrayHelper::map($configs, 'config_name', 'config_value');

            /**
             * 2、查出所有文件大小
             */
            $all_size = (new Query())
                    ->select('SUM(size)')
                    ->from(['File' => Uploadfile::tableName()])
                    ->where(['is_del' => 0])
                    ->column();
            $all_size = (double) $all_size[0];

            /*
             * 3、检查是否超出设定的警告值
             */
            $feedback = [
                'current_value' => \Yii::$app->formatter->asShortSize($all_size),
                'warning_value' => \Yii::$app->formatter->asShortSize((double) $configs['max_filesize_warning']),
                'max_value' => \Yii::$app->formatter->asShortSize((double) $configs['max_filesize_limit']),
                'remain_value' => \Yii::$app->formatter->asShortSize((double) $configs['max_filesize_limit'] - $all_size),
                'des' => '无',
            ];
            if ($all_size >= (double) $configs['max_filesize_warning']) {
                /**
                 * 查出所有管理员，并发送警告信息
                 */
                /* @var $rbacManager RbacManager */
                $rbacManager = \Yii::$app->authManager;
                $admins = $rbacManager->getItemUsers('r_admin');
                //拿到 GUID 发送企业微信
                $admins = array_unique(ArrayHelper::getColumn($admins, 'guid'));

                $feedback['des'] = '请注意！文件空间已经超出警戒线，请及时处理！';
                //发送通知
                $result = json_decode(NotificationManager::sendByView('schedule/_max_filesize_warning_html', $feedback, $admins, '空间占用超出警戒线', MCONLINE_WEB_ROOT), true);
                //发送错误时记录出错信息
                if ($result['errcode'] != 0) {
                    throw new Exception($result['errmsg'], $result['errcode']);
                }
            }

            $taskLog->result = 1;
            $taskLog->feedback = json_encode($feedback);
        } catch (Exception $ex) {
            $taskLog->result = 0;
            $taskLog->feedback = $ex->getMessage() . "\n" . $ex->getTraceAsString();
        }
        /*
         * 4、添加删除操作记录
         */
        $taskLog->save();
    }

}
