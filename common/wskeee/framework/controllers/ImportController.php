<?php

namespace wskeee\framework\controllers;

use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\utils\ExcelUtil;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

class ImportController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * 上传文件自动导入
     */
    public function actionUpload(){
        $upload = UploadedFile::getInstanceByName('import-file');
        if($upload != null)
        {
            $string = $upload->name;
            $excelutil = new ExcelUtil();
            $excelutil->load($upload->tempName);
            $columns = $excelutil->getSheetDataForColumn();
            $itemType = [];
            $itemB_A = [];
            $itemC_B = [];
            
            foreach ($columns as $column){
                $itemType = $this->mergeType($itemType, $column['data'][1]);
                $itemB_A = $this->customMerge($itemB_A,$column['data'][2],$column['data'][3]);
                $itemC_B = $this->customMerge($itemC_B,$column['data'][3],$column['data'][4]);
            }
           
            //var_dump($itemB_A);
            //var_dump($itemC_B);exit;
            $this->createItemType($itemC_B);
            $A_Name_Id = $this->create(array_flip(array_values($itemB_A)), null, Item::LEVEL_COLLEGE);//插入行业
            $B_Name_Id = $this->create($itemB_A, $A_Name_Id, Item::LEVEL_PROJECT);//插入层次/类型
            $this->create($itemC_B, $B_Name_Id, Item::LEVEL_COURSE);//插入专业/工种
        }
        return $this->render('upload');
    }
    /**
     * 创建项目数据
     * @param Array $itemA_B     array(a=>b) || array(A)
     * @param Array $bNameToIds  对应B 的id键值对，名称=>id
     * @param ingeger           项目等级
     */
    private function create($itemA_B,$bNameToIds,$level){
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        $parent_ids = [];
        foreach ($itemA_B as $value){
            $parent_ids[] = is_array($bNameToIds) ? $bNameToIds[$value] : $bNameToIds;
        }
        //添加到数据库
        $fwManager->addItems(array_keys($itemA_B), $level, $parent_ids);
        //查看创建后id
        $result = (new Query())
            ->select(['id','name'])
            ->where(['name'=>array_keys($itemA_B)])
            ->from(Item::tableName())
            ->all(Yii::$app->db);
        return ArrayHelper::map($result,'name','id');
    }
    /**
     * 添加类型
     * @param array $names
     */
    private function createItemType($names){
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        //添加到数据库
        $fwManager->addItemType($names);
    }
    
    /**
     * 合并两个数组
     * @param Array $target 最终合成数组
     * @param Array $arrA   作键  
     * @param Array $arrB   作值
     */
    private function customMerge($target,$arrA,$arrB){
        //从第二列开始取值
        array_splice($arrA, 0, 2);
        array_splice($arrB, 0, 2);
        foreach ($arrB as $index=>$value){
            $target[$value] = $arrA[$index];
        }
        return $target;
    }
    
    /**
     * 合并类型
     * @param array $target
     * @param array $arrs
     */
    private function mergeType($target,$arrs){
        array_splice($arrs, 0, 2);
        $target = array_merge($target, $arrs);
        return array_unique($target);
    }
}
