<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\need\controllers;

use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use wskeee\framework\models\Item;
use wskeee\rbac\components\Helper;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;



/**
 * Description of ImportController
 *
 * @author Administrator
 */
class BasedataImportController extends BasedataController {
    /* 数据导入状态 */

    private $feedback_data = [
        'data' => []//id,name,status
    ];

    /**
     * 批量导入
     */
    public function actionUpload() {
        $upload = UploadedFile::getInstanceByName('import-file');
        if ($upload != null) {
            $spreadsheet = IOFactory::load($upload->tempName);
            $indata = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);
            //导入前准备，转换到需要的格式
            $newdatas = $this->prepare($indata);
            //检查已经存在的基础数据，并且获取id
            $this->checkHasDone($indata, $newdatas);
            $result = 1;
            $msg = '';
            if ($this->checkInsertRbac($newdatas)) {
                //插入数据
                try {
                    $this->insertData($newdatas);
                    $result = 1;
                } catch (Exception $ex) {
                    $result = 0;
                    $msg = $ex->getMessage() . '\n' . $ex->getTraceAsString();
                }
            } else {
                $result = 0;
            }
            return $this->render('upload_result', [
                        'result' => $result,
                        'msg' => $msg,
                        'datas' => $newdatas,
            ]);
        }
        return $this->render('upload');
    }

    /**
     * 导入前准备，转换到需要的格式
     * @param array $indata   []
     */
    private function prepare(&$indata) {
        //A1__B1__C1 = [id,name,level,parent];
        $newdatas = [];
        /* 过滤没有用的行 */
        $indata = array_filter($indata, function($item) {
            foreach ($item as $v) {
                if ($v != null) {
                    return true;
                }
            }
            return false;
        });
        //删除表头
        unset($indata[0]);
        $indata = array_values($indata);
        //填补空出来的格子，等于上一格子一样
        foreach ($indata as $row_index => &$row) {
            $path = '';
            foreach ($row as $index => $value) {
                $row[$index] = trim($value);
                $value = trim($value);
                if ($row_index > 0 && $value == null) {
                    //如果当前格子为空，设置该格子值为上一格子值
                    $value = $row[$index] = $indata[$row_index - 1][$index];
                }
                if (empty($value))
                    continue;
                $parent_path = $path;
                //生成父级路径 eg:A1__B1__C1
                $path = $path == '' ? $value : $path . '__' . $value;
                //以父级路径为唯一key保存
                $newdatas[$path] = [
                    'id' => null,
                    'name' => $value,
                    'level' => $index + 1, //1：层次、类型 2：专业、工种 3：课程
                    'parent' => $parent_path,
                    'isExit' => 0, //是否存在
                ];
            }
        }
        unset($row);
        ArrayHelper::multisort($newdatas,'level',SORT_ASC);
        return $newdatas;
    }

    /**
     * 检查已经存在的基础数据，并且获取id
     * @param array $indata     原始数据 [层次，专业，课程]
     * @param array $newdatas   路径数据 [path => [id,name,level,parent]]
     */
    private function checkHasDone(&$indata, &$newdatas) {
        //------------------------
        //查询已经存在的层次、类型
        //------------------------
        $tableName = Item::tableName();
        $result = (new Query())
                ->select(['id', 'name'])
                ->from($tableName)
                ->where(['level' => 1, 'name' => ArrayHelper::getColumn($indata, 0)])
                ->all();
        //设置已存的ID
        foreach ($result as $index => $row) {
            $newdatas[$row['name']]['id'] = $row['id'];
            $newdatas[$row['name']]['isExit'] = 1;
        }
        //------------------------
        //查询已经存在的专业、工种
        //------------------------
        $result = (new Query())
                ->select(['B.id', 'B.name', 'A.id as parent_id', 'A.name as parent_name', 'CONCAT(A.name,\'__\',B.name) as Path'])
                ->from(['B' => $tableName])
                ->leftJoin(['A' => $tableName], 'B.parent_id = A.id')
                ->where(['B.level' => 2, 'B.name' => ArrayHelper::getColumn($indata, 1), 'A.name' => ArrayHelper::getColumn($indata, 0)])
                ->all();

        //设置已存的ID
        foreach ($result as $index => $row) {
            if(isset($newdatas[$row['Path']])){
                $newdatas[$row['Path']]['id'] = $row['id'];
                $newdatas[$row['Path']]['isExit'] = 1;
            }
        }
        //------------------------
        //查询已经存在的课程
        //------------------------
        $result = (new Query())
                ->select(['C.id', 'C.name', 'B.id as parent_id', 'B.name as parent_name', 'CONCAT(A.name,\'__\',B.name,\'__\',C.name) as Path'])
                ->from(['C' => $tableName])
                ->leftJoin(['B' => $tableName], 'C.parent_id = B.id')
                ->leftJoin(['A' => $tableName], 'B.parent_id = A.id')
                ->where(['C.level' => 3, 'C.name' => ArrayHelper::getColumn($indata, 2), 'B.name' => ArrayHelper::getColumn($indata, 1), 'A.name' => ArrayHelper::getColumn($indata, 0)])
                ->all();
        //设置已存的ID
        foreach ($result as $index => $row) {
            if(isset($newdatas[$row['Path']])){
                $newdatas[$row['Path']]['id'] = $row['id'];
                $newdatas[$row['Path']]['isExit'] = 1;
            }
        }
    }

    /**
     * 检测是否具备插入权限
     * @param array $newdatas 路径数据 [path => [id,name,level,parent]]
     * @return bool true/false 权限检测是否通过
     */
    private function checkInsertRbac(&$newdatas) {
        $pass = true;
        $moduleId = $this->module->id;
        //每个等级插入数据权限
        $insertLevels = [
            1 => Helper::checkUrl("/$moduleId/college/create"),
            2 => Helper::checkUrl("/$moduleId/project/create"),
            3 => Helper::checkUrl("/$moduleId/course/create"),
        ];
        foreach ($newdatas as &$item) {
            if ($item['id'] == null) {
                //检查创建当前级别数据是否有权限
                if ($insertLevels[$item['level']]) {
                    //有权限，设置准备创建状态
                    $item['hasRbac'] = 1;
                } else {
                    $item['hasRbac'] = 0;
                    if ($pass) {
                        $pass = false;
                    }
                }
            }
        }
        unset($item);
        return $pass;
    }

    /**
     * 插入数据
     * @param array $newdatas   路径数据 [path => [id,name,level,parent]]
     */
    private function insertData($newdatas) {
        //------------------------
        //组装数据
        //------------------------
        $time = time();
        $start_id = $this->getAutoIncrementId('ccoa_framework_item');
        //待插入数据
        $rows = [];
        //设置新增ID
        foreach ($newdatas as $key => &$item) {
            if ($item['id'] == null) {
                $item['id'] = $start_id ++;
                $item['new'] = 1;
            }
        }
        unset($item);
        //组装数据需求插入的数据
        foreach ($newdatas as $item) {
            if (isset($item['new'])) {
                //id,name,parent_id,level,created_at,updated_at
                $rows [] = [
                    $item['id'],
                    $item['name'],
                    empty($item['parent']) ? null : $newdatas[$item['parent']]['id'],
                    $item['level'],
                    $time,
                    $time
                ];
            }
        }
        Yii::$app->db->createCommand()->batchInsert(Item::tableName(), ['id', 'name', 'parent_id', 'level', 'created_at', 'updated_at'], $rows)->execute();
    }

    /**
     * 获取表最新自增ID
     * @param string $tableName
     */
    private function getAutoIncrementId($tableName) {
        $cmd = Yii::$app->db->createCommand("SHOW TABLE STATUS LIKE '$tableName'");
        $result = $cmd->queryAll();
        return (integer) $result[0]['Auto_increment'];
    }

}
