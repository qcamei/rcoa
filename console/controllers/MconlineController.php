<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace console\controllers;

use common\models\mconline\McbsActivityFile;
use common\models\mconline\McbsCourseActivity;
use common\models\ScheduledTaskLog;
use Exception;
use wskeee\webuploader\models\Uploadfile;
use Yii;
use yii\console\Controller;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Description of MconlineController
 *
 * @author Administrator
 */
class MconlineController extends Controller {

    /**
     * 删除到期文件
     * 
     * 1、查出所有过期的关联（活动与文件关联），添加到删除列表
     * 2、从过期的关联中检查看有没有其它未过期的关联，有即移出删除列表
     * 3、删除物理文件
     * 4、在文件表中标记文件已删除
     * 5、添加删除操作记录
     */
    public function actionCheckExpireFile() {
        try {
            /**
             * 1、查出所有过期的关联（活动与文件关联），添加到删除列表
             */
            $expireFiles = (new Query())
                    ->select(['ActivityFile.file_id', 'File.name AS file_name', 'File.path AS file_path', 'File.size AS file_size'])
                    ->from(['ActivityFile' => McbsActivityFile::tableName()])
                    ->leftJoin(['File' => Uploadfile::tableName()], 'ActivityFile.file_id = File.id')
                    ->where(['<', 'ActivityFile.expire_time', time()])
                    ->andWhere(['ActivityFile.is_del' => 0])
                    ->groupBy('ActivityFile.file_id')
                    ->all();
            /**
             * 2、从过期的关联中检查看有没有其它未过期的关联，有即移出删除列表
             */
            $unExpireFiles = (new Query())
                    ->select(['ActivityFile.file_id'])
                    ->from(['ActivityFile' => McbsActivityFile::tableName()])
                    ->where(['>', 'ActivityFile.expire_time', time()])
                    ->andWhere(['in', 'ActivityFile.file_id', array_unique(ArrayHelper::getColumn($expireFiles, 'file_id'))])
                    ->all();

            $unExpireFiles = ArrayHelper::map($unExpireFiles, 'file_id', true);

            /**
             * 3、删除物理文件
             */
            $result = [];
            $file_results = [];          //{file_id,file_path,result,mes}
            $all_size = 0;          //全部大小
            $fail_num = 0;          //失败数
            $success_num = 0;       //成功数
            $delFileIds = [];    //已删除文件的ID

            foreach ($expireFiles as $expireFile) {
                if (!isset($unExpireFiles[$expireFile['file_id']])) {
                    //设置已经检查过
                    $unExpireFiles[$expireFile['file_id']] = true;
                    //创建一条删除记录
                    $result = [
                        'file_id' => $expireFile['file_id'],
                        'file_path' => $expireFile['file_path'],
                    ];
                    if (!file_exists($dirname)) {
                        $result['result'] = 0;
                        $result['mes'] = '文件不存在！';
                        $fail_num++;
                    }
                    if (is_file($dirname) || is_link($dirname)) {
                        try {
                            unlink($dirname);
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
            $result['file_results'] = $file_results;
            $result['all_size'] = $all_size;
            $result['fail_num'] = $fail_num;
            $result['success_num'] = $success_num;
            $result['mark_del_result'] = 1;
            /**
             * 4、在文件表中标记文件已删除
             */
            try {
                Yii::$app->db->createCommand()->update(Uploadfile::tableName(), ['is_del' => 1], ['in', 'id', $delFileIds])->execute();
            } catch (Exception $ex) {
                $result['mark_del_result'] = 0;
                $result['mark_del_mes'] = $ex->getMessage();
            }

            /*
             * 5、添加删除操作记录
             */

            $taskLog = new ScheduledTaskLog();
            $taskLog->type = ScheduledTaskLog::TYPE_MCONLINE_CHECK_EXPIRE_FILE;
            $taskLog->action = $this->route;
            $taskLog->result = 1;
            $taskLog->feedback = json_encode($result);
        } catch (Exception $ex) {
            $taskLog = new ScheduledTaskLog();
            $taskLog->type = ScheduledTaskLog::TYPE_MCONLINE_CHECK_EXPIRE_FILE;
            $taskLog->action = $this->route;
            $taskLog->result = 0;
            $taskLog->feedback = $ex->getTraceAsString();
        }
        $taskLog->save();
    }

}
