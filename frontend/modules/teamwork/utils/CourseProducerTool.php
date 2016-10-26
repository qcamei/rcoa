<?php

namespace frontend\modules\teamwork\utils;

use common\models\teamwork\CourseProducer;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\base\Component;
use yii\caching\Cache;
use yii\db\Query;
use yii\di\Instance;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CourseProducerTool
 *
 * @author Kiwi°
 */
class CourseProducerTool extends Component {
    
    private static $instance = null;
    /**
     * @var 超时时长
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
    public $cacheKey = 'course_producer';
    
    /**
     * 课程制作人数据
     * @var array [
     * int course_id        课程id
     * int producer         制作人
     * ]
     */
    private $courseProducer;


    public function init() 
    {
        parent::init();
        
        $this->cache = Instance::ensure([
                'class' => 'yii\caching\FileCache',
                'cachePath' => Yii::getAlias('frontend').'/runtime/cache'
            ], Cache::className());

        $this->loadFromCache();
    }
    
    /**
     * 获取该课程下的所有课程制作人
     * @param type $course_id           课程ID
     * @return type
     */
    public function getCourseProducer($course_id) 
    {
        $producer = [];
        foreach ($this->courseProducer as $producers) {
            if($producers['course_id'] != $course_id) continue;
            $producer[] = $producers['producer'];
        }
        return $producer;
    }

    /**
     * 取消缓存
     */
    public function invalidateCache() 
    {
        if(!$this->cache !== null)
        {
            $this->cache->delete($this->cacheKey);
            $this->courseProducer = [];
        }
    }
    
    /**
     * 从缓存中获取数据
     */
    public function loadFromCache()
    {
        if ($this->courseProducer !== null || !$this->cache instanceof Cache) {
            return;
        }
        $data = $this->cache->get($this->cacheKey);
        
        if(is_array($data) && (isset($data[2]) && (time() - $data[2]<self::TIME_OUT)) && isset($data[0],$data[1],$data[2]))
        {
            //从缓存取出课程制作人数据
            list($this->courseProducer) = $data;
            return;
        }
        //没有缓存则从数据库获取数据
        $this->courseProducer = $this->getCourseProducerDatas();
        
        $this->cache->set($this->cacheKey, [$this->courseProducer,  time()]);
    }
    
    /**
     * 获取所有课程制作人数据
     * @return arrya (course_id, producer)
     */
    private function getCourseProducerDatas(){
        $query = (new Query())
                ->select(['course_id','producer'])
                ->from(CourseProducer::tableName());
        return $query->all(Yii::$app->db);
    }
    
    /**
     * 获取单例
     * @return CourseProducerTool
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new CourseProducerTool();
        }
        return self::$instance;
    }
}
