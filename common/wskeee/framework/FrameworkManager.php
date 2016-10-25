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
     * 添加一个项目基础数据
     * @param string $name              名称
     * @param integer $level            等级 Item::LEVEL_COLLEGE / Item::LEVEL_PROJECT / Item::LEVEL_COURSE
     * @param integer $parent_id        父级id
     * @param string $des               描述
     * @param boolean $clearCache       清除缓存    
     * @return integer 新加或更新后id
     */
    public function addItem($name,$level,$parent_id,$des,$clearCache=1){
        $item = Item::find(['name'=>$name]);
        if(!$item)
            $item = new Item ();
        $item->name = $name;
        $item->level = $level;
        $item->parent_id = $parent_id;
        $item->des = $des;
        if($item->validate() && $item->sava()){
            if($clearCache)
                $this->invalidateCache ();
            return $item->id;
        }
        return null;
    }
    
    /**
     * 添加多个项目基础数据
     * @param array $names                          多个名称集
     * @param array | integer $level                等级 Item::LEVEL_COLLEGE / Item::LEVEL_PROJECT / Item::LEVEL_COURSE
     * @param array | integer $parent_id            父级id
     * @param boolean $clearCache                   清除缓存 
     */
    public function addItems($names,$level,$parent_id,$clearCache=1){
        $doneItems = Item::find()
                        ->select(['id','name'])
                        ->asArray()
                        ->where(['name'=>$names])
                        ->all();
        //已经存在的数据
        $doneNames = ArrayHelper::map($doneItems,'name','id');
        //需要新建的数据
        //$newNames = array_diff($doneNames, $names);
        $rows = [];
        foreach($names as $index=>$name){
            if(!isset($doneNames[$name]))
                $rows [] = [
                    $name,  
                    is_array ($level) ? $level[$index] : $level,//如果是array，添加对应值
                    is_array ($parent_id) ? $parent_id[$index] : $parent_id//如果是array，添加对应值
                ];
        }
        if(count($rows)==0)return;
        Yii::$app->db->createCommand()->batchInsert(Item::tableName(), ['name','level','parent_id'], $rows)->execute();
    }
    
    /**
     * 创建类型
     * @param array $names
     * @param boolean $clearCashe
     */
    public function addItemType($names,$clearCashe){
        $doneItems = ItemType::find()
                        ->select(['id','name'])
                        ->asArray()
                        ->where(['name'=>$names])
                        ->all();
        //已经存在的数据
        $doneNames = ArrayHelper::map($doneItems,'name','id');
        //需要新建的数据
        //$newNames = array_diff($doneNames, $names);
        $rows = [];
        foreach($names as $index=>$name){
            if(!isset($doneNames[$name]))
                $rows [] = [$name];
        }
        if(count($rows)==0)return;
        Yii::$app->db->createCommand()->batchInsert(ItemType::tableName(), ['name'], $rows)->execute();
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
