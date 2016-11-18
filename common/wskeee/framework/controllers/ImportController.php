<?php

namespace wskeee\framework\controllers;

use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\utils\TeamworkBatchAdd;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\utils\ExcelUtil;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

class ImportController extends Controller
{
    
    public function behaviors()
    {
        return [
             //验证delete时为post传值
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            //access验证是否有登录
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }
    
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
            $itemTypes = [];        //行业
            $items = [];            //层次/类型
            $itemChilds = [];       //专业/工种
            $courses = [];          //课程
            $teachers = [];         //主讲讲师
            $buildModes = [];        //建设模式
            $learnScores = [];       //学分
            $learnTimes = [];        //学时
            $videoTimeLengthes = []; //视频时长
            $questionMete = [];     //题量
            $caseNumber = [];       //案例数
            $activityNumber = [];   //活跃数
            $teams = [];            //团队
            $producer = [];         //开发人员
            $courseOps  = [];       //运维人
            foreach ($columns as $column){
                $itemTypes = $this->unique($itemTypes, $column['data'][0]);
                $items = $this->merge($items, $column['data'][1], $column['data'][0]);
                $itemChilds = $this->merge($itemChilds, $column['data'][1], $column['data'][2]);
                $courses = $this->merge($courses, $column['data'][2], $column['data'][3]);
                $teachers = $this->merge($teachers, $column['data'][4], $column['data'][3]);
                $buildModes = $this->merge($buildModes, $column['data'][5], $column['data'][3]);
                $learnScores = $this->merge($learnScores, $column['data'][6], $column['data'][3]);
                $learnTimes = $this->merge($learnTimes, $column['data'][7], $column['data'][3]);
                $videoTimeLengthes = $this->merge($videoTimeLengthes, $column['data'][8], $column['data'][3]);
                $questionMete = $this->merge($questionMete, $column['data'][9], $column['data'][3]);
                $caseNumber = $this->merge($caseNumber, $column['data'][10], $column['data'][3]);
                $activityNumber = $this->merge($activityNumber, $column['data'][11], $column['data'][3]);
                $teams = $this->merge($teams, $column['data'][12], $column['data'][3]);
                $producer = $this->merge($producer, $column['data'][13], $column['data'][3]);
                $courseOps = $this->merge($courseOps, $column['data'][14], $column['data'][3]);
            }
            
            $courseInfoArray = $this->againPackaging([$teachers, $buildModes, 
                $learnScores, $learnTimes, $videoTimeLengthes, $questionMete, $caseNumber, 
                $activityNumber, $teams, $courseOps]);
            $itemTypeIds = $this->createFmItemType($itemTypes);                                       //插入基础数据 - 行业
            $itemIds = $this->createFmItem(array_unique(array_values($items)), Item::LEVEL_COLLEGE);  //插入基础数据 - 层次/类型
            $itemChildIds = $this->createFmItems($itemChilds, $itemIds, Item::LEVEL_PROJECT);        //插入基础数据 - 专业/工种
            $courseIds = $this->createFmItems($courses, $itemChildIds, Item::LEVEL_COURSE);          //插入基础数据 - 课程
            $projectIds = $this->createTwItems($items, $itemTypeIds, $itemIds, $itemChildIds);//插入数据到Teamwork_item表里
            $twCourseIds = $this->createTwCourse($courseIds, $projectIds, $courseInfoArray);    //插入数据到Teamwork_course表里
            $this->createTwProducer($twCourseIds, $producer);
            $this->createTwCoursePhase($twCourseIds, $templateType = null);
            $this->createTwCourseLink($twCourseIds, $templateType = null);
        }
        return $this->render('upload');
    }
    
    /**
     * 添加基础行业数据
     * @param FrameworkManager $fwManager
     * @param array $names                  行业数据
     * @return array
     */
    private function createFmItemType($names){
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        //添加到数据库
        $doneItemTypeName = $fwManager->addItemType($names);
        
        $results = ItemType::find()
                   ->where(['name' => $doneItemTypeName])
                   ->asArray()
                   ->all();
        
        return ArrayHelper::map($results, 'name', 'id');
    }
    
    /**
     * 创建基础层次/类型数据
     * @param FrameworkManager $fwManager
     * @param array $item                   层次/类型数据
     * @param ingeger $level                项目等级
     * @return array
     */
    private function createFmItem($item, $level){
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        //添加到数据库
        $doneNames = $fwManager->addItem($item, $level);
        //查看创建后id
        $results = (new Query())
                ->select(['id','name'])
                ->where(['name' => array_values($doneNames), 'level' => $level])
                ->from(Item::tableName())
                ->all(Yii::$app->db);
        
        return ArrayHelper::map($results, 'name', 'id', 'id');
    }
    
    /**
     * 创建基础项目数据
     * @param FrameworkManager $fwManager
     * @param array $items                  a => array(b => c)
     * @param array $bNameToIds             对应B 的id键值对，名称 => id
     * @param ingeger  $level               项目等级
     * @return array
     */
    private function createFmItems($items, $bNameToIds, $level){
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        $parentIds = [];
        foreach ($items as $index => $element) {
            foreach ($bNameToIds as $key => $itemIds) {
                foreach ($itemIds as $name => $id) {
                    if($element == $name)
                        $parentIds[$index] = $id;
                }
            }
        }
        
        //添加到数据库
        $doneName = $fwManager->addItems(array_keys($items), $level, $parentIds);
        //查看创建后id
        $results = (new Query())
            ->select(['id','name', 'parent_id'])
            ->where(['name' => array_values($doneName), 'level' => $level, 'parent_id' => array_values($parentIds)])
            ->from(Item::tableName())
            ->all(Yii::$app->db);
        
        return ArrayHelper::map($results, 'name', 'id', 'parent_id');
    }
    
    /** 
     * 创建团队工作项目数据
     * @param TeamworkBatchAdd $twBatchAdd
     * @param array $items                       //上传的数据
     * @param array $itemTypeIds                 //基础行业数据
     * @param array $itemIds                     //基础层次/类型数据
     * @param array $itemChildIds                //基础专业/工种数据
     * @return array
     */
    private function createTwItems($items, $itemTypeIds, $itemIds, $itemChildIds)
    {
        $itemId = [];
        $itemTypeId = [];
        foreach ($items as $itemTypeName => $itemName) {
            foreach ($itemIds as $index => $items) {
                foreach ($items as $name => $id) {
                    if($name == $itemName)
                        $itemId[$id] = $itemTypeName;
                }
            }
            
        }
        foreach ($itemId as $id => $name) {
            foreach ($itemTypeIds as $typeName => $typeId) {
                if($name == $typeName)
                   $itemTypeId[$id] = $typeId;
            }
        }
        
        /* @var $twBatchAdd TeamworkBatchAdd */
        $twBatchAdd = TeamworkBatchAdd::getInstance();
        //添加到数据库
        $itemChildId = $twBatchAdd->addTwItem($itemTypeId, $itemChildIds);
        
        //查找创建后id
        $results = (new Query())
                   ->select(['id', 'item_child_id'])
                   ->from(ItemManage::tableName())
                   ->where(['item_child_id' => array_values($itemChildId)])
                   ->all(Yii::$app->db);
        
        return ArrayHelper::map($results, 'item_child_id', 'id');
    }
    
    /**
     * 创建团队工作课程数据
     * @param TeamworkBatchAdd $twBatchAdd
     * @param array $courseIds                      基础课程数据
     * @param array $projectIds                     Teamwork_item表ID
     * @param array $courseInfoArray                上传上来的数据
     * @return array
     */
    private function createTwCourse($courseIds, $projectIds, $courseInfoArray)
    {
        /* @var $twBatchAdd TeamworkBatchAdd */
        $twBatchAdd = TeamworkBatchAdd::getInstance();
        $courses = [];
        foreach ($courseIds as $index => $course) 
            $courses[$projectIds[$index]] = $course;
       
        //添加到数据库
        $doneTwCourseIds = $twBatchAdd->addTwCourse($courses, $courseInfoArray);
        
        //查找创建后id
        $results = (new Query())
                   ->select(['Tw_course.id', 'Fm_item.name'])
                   ->from(['Tw_course' => CourseManage::tableName()])
                   ->leftJoin(['Fm_item' => Item::tableName()], 'Fm_item.id = Tw_course.course_id')
                   ->where(['Tw_course.course_id' => array_values($doneTwCourseIds)])
                   ->all(Yii::$app->db);
          
        return ArrayHelper::map($results, 'name', 'id');
    }

    /**
     * 创建团队工作课程制作人
     * @param TeamworkBatchAdd $twBatchAdd      
     * @param array $courseIds                  课程
     * @param array $producer                   制作人
     */
    private function createTwProducer($courseIds, $producer)
    {
        $producers = $this->stringToArray($producer);
        /* @var $twBatchAdd TeamworkBatchAdd */
        $twBatchAdd = TeamworkBatchAdd::getInstance();
        $cIdProducers = [];
        foreach ($courseIds as $courseName => $id) {
            if(isset($producers[$courseName]))
                $cIdProducers[$id] = $producers[$courseName];
        }
       
        //添加到数据库
        $twBatchAdd->addTwProducer($cIdProducers);
    }
    
    private function createTwCoursePhase($courseIds, $templateType = null)
    {
        /* @var $twBatchAdd TeamworkBatchAdd */
        $twBatchAdd = TeamworkBatchAdd::getInstance();
       
        //添加到数据库
        $twBatchAdd->addCoursePhase($courseIds, $templateType);
    }
    
    private function createTwCourseLink($courseIds, $templateType = null)
    {
        /* @var $twBatchAdd TeamworkBatchAdd */
        $twBatchAdd = TeamworkBatchAdd::getInstance();
       
        //添加到数据库
        $twBatchAdd->addCourseLink($courseIds, $templateType);
    }
    
    /**
     * 过滤数组重复值
     * @param array $target     最终数组
     * @param array $arrs       作值
     */
    private function unique($target, $arrs){
        array_splice($arrs, 0, 2);
        $target = array_merge($target, $arrs);
        return array_unique($target);
    }
    
    /**
     * 合并两个数组
     * @param Array $target 最终合成数组
     * @param Array $arrA   作键  
     * @param Array $arrB   作值
     */
    private function merge($target, $arrA, $arrB){
        //从第二列开始取值
        array_splice($arrA, 0, 2);
        array_splice($arrB, 0, 2);
        foreach ($arrB as $index=>$value)
            $target[$value] = $arrA[$index];
            
        return $target;
    }
    
    /**
     * 重组相同键值的数组
     * @param array $array          集成数组
     * @return array
     */
    private function againPackaging($array)
    {
        $target = [];
        foreach ($array as $arr) {
            foreach ($arr as $key => $value) {
                $target[$key][] = $value;
            }
        }
        
        return $target;
    }
    
    /**
     * 字符串转数组
     * @param array $array         数组
     * @return array
     */
    public function stringToArray($array)
    {
        $target = [];
        foreach ($array as $course => $nickname) {
            if(strpos($nickname, ',') == false)
                $nickname = $nickname.',';
            
            if(strpos($nickname, ','))  
                $target[$course] = array_filter(explode(',', $nickname));
            else if (strpos($nickname, '，')) 
                $target[$course] = array_filter(explode('，', $nickname));
            
        }
        
        return $target;
    }
}
