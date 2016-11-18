<?php
namespace wskeee\framework;

use wskeee\framework\models\FWItem;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\base\Component;
use yii\caching\Cache;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;



/**
 * Description of ProjectManager
 *
 * @author wskeee
 */
class FrameworkManager extends Component 
{
    /*
     * 超时时长
     */
    const TIME_OUT = 10*60;
    
    public $url = '';
    /**
     * @var Cache|array|string the cache used to improve framework performance. This can be one of the following:
     *
     * - an application component ID (e.g. `cache`)
     * - a configuration array
     * - a [[\yii\caching\Cache]] object
     *
     * When this is not set, it means caching is not enabled.
     */
    public $cache;
    /**
     * @var string the key used to store 项目 data in cache
     * @see cache
     * @since 1.0.0
     */
    public $cacheKey = 'wskeee_framework';

    /**
     * @var FWItem[] all auth items (name => FWItem)
     */
    protected $items;
    /**
     * @var array 项目之间关系 (childName => list of parents)
     */
    protected $parents;
    
    /**
     * @var array 项目与子项关系(name => list of child) 
     */
    protected $childs;


    public function init() 
    {
        parent::init();
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
        }
        $this->loadFromCache();
    }
    
    /**
     * 创建行业
     * @param array $names              
     * @param boolean $clearCashe
     */
    public function addItemType($names, $clearCashe = null){
        $doneItems = ItemType::find()
                    ->select(['id','name'])
                    ->where(['name'=>$names])
                    ->asArray()
                    ->all();
        //已经存在的数据
        $doneNames = ArrayHelper::map($doneItems,'name','id');
        //需要新建的数据
        //$newNames = array_diff($doneNames, $names);
        $rows = [];
        foreach($names as $index => $name){
            if(!isset($doneNames[$name]))
                $rows[] = [
                    'name' => $name
                ];
        }
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            $number = Yii::$app->db->createCommand()->batchInsert(ItemType::tableName(), ['name'], $rows)->execute();
            if(count($rows) > 0 && $number > 0) {
                $trans->commit();  //提交事务
                Yii::$app->getSession()->setFlash('success','操作成功！');
            }
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
        
        return ArrayHelper::getColumn($rows, 'name');
    }
    
    /**
     * 添加层次/类型
     * @param array $name               名称
     * @param integer $level            等级 Item::LEVEL_COLLEGE / Item::LEVEL_PROJECT / Item::LEVEL_COURSE
     * @param boolean $clearCache       清除缓存    
     * @return integer 新加或更新后id
     */
    public function addItem($names, $level, $clearCache = 1){
        $items = Item::find()
                ->where(['name'=> $names])
                ->asArray()
                ->all();
        
        //已经存在的数据
        $doneNames = ArrayHelper::map($items, 'name', 'id');
        $rows = [];
        foreach($names as $index => $name){
            if(!isset($doneNames[$name])){
                $rows [] = [
                    'name' => $name,
                    'level' => $level,
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
            }
        }
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            $number = Yii::$app->db->createCommand()->batchInsert(Item::tableName(), [
                    'name',  'level', 'created_at', 'updated_at'], $rows)->execute();
            if(count($rows) > 0 && $number > 0) {
                $trans->commit();  //提交事务
                Yii::$app->getSession()->setFlash('success','操作成功！');
            }
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
        
        return ArrayHelper::getColumn($rows, 'name');
    }
    
    /**
     * 添加多个项目基础数据
     * @param array $names                          多个名称集
     * @param integer $level                        等级 
     * @param array $parentIds                      父级id
     * @param boolean $clearCache                   清除缓存 
     */
    public function addItems($names, $level, $parentIds, $clearCache=1)
    {
        $doneItems = Item::find()
                    ->select(['id','name'])
                    ->where(['name' => $names, 'level' => $level, 'parent_id' => array_values($parentIds)])
                    ->asArray()
                    ->all();
        
        //已经存在的数据
        $doneNames = ArrayHelper::map($doneItems,'name','id');
        
        $rows = [];
        foreach($names as $index => $name){
            if(!isset($doneNames[$name]) && isset($parentIds[$name]))
                $rows [] = [
                    'name' => $name,  
                    'level' => $level,
                    'created_at' => time(),
                    'updated_at' => time(),
                    'parent_id' => $parentIds[$name]
                ];
        }
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            $number = Yii::$app->db->createCommand()->batchInsert(Item::tableName(), [
                    'name',  'level', 'created_at', 'updated_at', 'parent_id'], $rows)->execute();
        
            if(count($rows) > 0 && $number > 0 && !empty($parentIds)) {
                $trans->commit();  //提交事务
                Yii::$app->getSession()->setFlash('success','操作成功！');
            }
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
        
        return ArrayHelper::getColumn($rows, 'name');
    }
    
    /**
     * 获取架构数据
     * @param int $itemId
     * @return FWItem
     */
    public function getItemById($itemId)
    {
        if(isset($this->items[$itemId]))
            return $this->items[$itemId];
        else
            return null;
    }
    
    /**
     * return array 所有项目数据
     */
    public function getColleges()
    {   
        $items = [];
        foreach($this->items as $id => $item)
        {
            if($item->level == FWItem::LEVEL_COLLEGE)
                $items [] = $item;
        }
        return $items;
    }
    
    /**
     * 
     * @return array 所有子项目数据
     */
    public function getProjects()
    {
        $items = [];
        foreach($this->items as $id => $item)
        {
            if($item->level == FWItem::LEVEL_PROJECT)
                $items [] = $item;
        }
        return $items;
    }
    
    /**
     * 
     * @return array 所有课程数据
     */
    public function getCourses()
    {
        $items = [];
        foreach($this->items as $id => $item)
        {
            if($item->level == FWItem::LEVEL_COURSE)
                $items [] = $item;
        }
        return $items;
    }

    /**
     * 获取 itemId 的子项目
     * @param int $itemId 项目id
     * @return array 子项目
     */
    public function getChildren($itemId)
    {
        if(!isset($this->childs[$itemId]))
            return [];
        return $this->childs[$itemId];
    }
    
    /**
     * 取消缓存
     */
    public function invalidateCache() 
    {
        if(!$this->cache !== null)
        {
            $this->cache->delete($this->cacheKey);
            $this->items = null;
            $this->parents = null;
            $this->childs = null;
        }
    }

    /**
     * 从缓存中获取数据
     */
    public function loadFromCache()
    {
        if ($this->items !== null || !$this->cache instanceof Cache) {
            return;
        }
        $data = $this->cache->get($this->cacheKey);
        $time;
        if(is_array($data) && (isset($data[2]) && (time() - $data[2]<self::TIME_OUT)) && isset($data[0],$data[1],$data[2]))
        {
            list($this->items,$this->childs,$time) = $data;
            return;
        }
        
        
        $this->items = [];
        $datas = Item::find()->all();
        foreach ($datas as $item)
            $this->items[$item["id"]] = $this->populateItem($item);

        $this->childs = [];
        foreach($this->items as $id => $item)
        {
            if($item->parent_id !== null)
                $this->childs[$item->parent_id][] = $item;
        }
        $this->cache->set($this->cacheKey, [$this->items,$this->childs,  time()]);
        
    }
    
    /**
     * @var $item ['id','name','level','parent_id','des','created_at','updated_at']
     */
    private function populateItem($item)
    {
        return new FWItem($item);
    }
}
