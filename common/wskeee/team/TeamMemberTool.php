<?php
namespace wskeee\team;

use common\models\team\Team;
use common\models\team\TeamMember;
use Yii;
use yii\base\Component;
use yii\caching\Cache;
use yii\db\Query;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TeamMemberTool
 *
 * @author Administrator
 */
class TeamMemberTool extends Component {
    
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
    public $cacheKey = 'wskeee_teamember';
    
    /**
     *  团队数据
     * @var array   [
     *  int     id,     团队id
     *  string  name,   团队名称
     *  int     type,   团队类别
     *  string  image,  团队背景图
     *  int     index,  索引
     *  is_delete   
     * ]
     * 
     */
    private $teams;
    
    /**
     * 团队成员数据
     * @var arrya   [teamMember_id => [
     *  int     id,             团队成员id
     *  int     team_id,        团队id
     *  string  u_id,           用户id
     *  string  is_leader,      是否为队长：Y为是，N为否
     *  int     index,          索引
     *  int     position_id,    职位ID
     *  string  is_delete,      是否删除：N否，Y是  
     * ]];
     */
    private $teamMembers;
    
    /**
     * 团队id => 团队成员
     * @var array (team_id => teamMember)
     */
    private $team_member_map;
    
    public function init() 
    {
        parent::init();
        $this->cache = Instance::ensure([
                'class' => 'yii\caching\FileCache',
                'cachePath' => FRONTEND_DIR.'/runtime/cache'
            ], Cache::className());
        $this->loadFromCache();
    }
    
    /**
     * 获取团队数据
     * @param integer|array $team_id  团队id
     * @return  array   [
     *  int     id,     团队id          <br/>
     *  string  name,   团队名称        <br/>
     *  int     type,   团队类别        <br/>
     *  string  image,  团队背景图      <br/>
     *  int     index,  索引            <br/>
     *  string  is_delete               <br/>
     * ]
     */
    public function getTeamById($team_id){
        if(is_array($team_id))
        {
            $results = [];
            foreach($team_id as $id){
                $results [] = $this->teams[$id];
            }
            return $results;
        }else
            return $this->teams[$team_id];
    }
    
    /**
     * 获取团队成员数据
     * @param integer|array $teamMemberId        团队成员id
     * @return array [
     *  int     id,             团队成员id                  <br/>
     *  int     team_id,        团队id                      <br/>
     *  string  u_id,           用户id                      <br/>    
     *  string  is_leader,      是否为队长：Y为是，N为否     <br/>
     *  int     index,          索引                        <br/>
     *  int     position_id,    职位ID                      <br/>
     *  string  is_delete,      是否删除：N否，Y是           <br/>
     * ]
     * @see getUserTeamMembers  获取用户所有团队成员
     */
    public function getTeammemberById($teamMemberId){
        if(is_array($teamMemberId))
        {
            $results = [];
            foreach($teamMemberId as $id){
                if(isset($this->teamMembers[$id]))
                    $results [] = $this->teamMembers[$id];
            }
            return $results;
        }else if(isset ($this->teamMembers[$teamMemberId]))
            return $this->teamMembers[$teamMemberId];
        else {
            return [];
        }
    }
    
    /**
     * 获取团队下所有成员
     * @param int $team_id  团队id
     * @param boolean $include_is_delete 是否包括已删除成员，默认不包括
     * @return array (team_id = > [teammember,teammember])
     */
    public function getTeamMembersByTeamId($team_id,$include_is_delete=false){
        $results = [];
        foreach($this->teamMembers AS $teamMember){
            if($teamMember['team_id'] == $team_id)
            {
                if($include_is_delete || $teamMember['is_delete'] == 'N')
                    $results [] = $teamMember;
            }
        }
        return $results;
    }
    /**
     * 获取用户所属团队
     * @param string $user_id       用户id
     * @param boolean $include_is_delete 是否包括已删除成员，默认不包括
     * @return array (team,team,...)
     */
    public function getUserTeam($user_id,$include_is_delete=false){
        $results = [];
        foreach ($this->teamMembers as $teammeber) {
            if($teammeber['u_id'] == $user_id){
                if($include_is_delete || $teammeber['is_delete'] == 'N')
                    $results [] = $this->teams[$teammeber['team_id']];
            }
        }
        return $results;
    }
    
    /**
     * 获取用户所有团队成员
     * @param string $user_id           用户id
     * @param boolean $include_is_delete 是否包括已删除成员，默认不包括
     * @return array(teamember,...)
     */
    public function getUserTeamMembers($user_id,$include_is_delete=false){
        $results = [];
        foreach ($this->teamMembers as $teammeber) {
            if($teammeber['u_id'] == $user_id){
                if($include_is_delete || $teammeber['is_delete'] == 'N')
                    $results [] = $teammeber;
            }
        }
        return $results;
    }
    
    /**
     * 取消缓存
     */
    public function invalidateCache() 
    {
        if(!$this->cache !== null)
        {
            $this->cache->delete($this->cacheKey);
            $this->teams = [];
            $this->teamMembers = [];
            $this->team_member_map = [];
        }
    }
    
    /**
     * 从缓存中获取数据
     */
    public function loadFromCache()
    {
        if ($this->teams !== null || !$this->cache instanceof Cache) {
            return;
        }
        $data = $this->cache->get($this->cacheKey);
        
        if(is_array($data) && (isset($data[2]) && (time() - $data[2]<self::TIME_OUT)) && isset($data[0],$data[1],$data[2]))
        {
            //从缓存取出团队与团队成员数据
            list($this->teams,$this->teamMembers) = $data;
            return;
        }
        //没有缓存则从数据库获取数据
        $this->teams = $this->getTeamsDatas();
        $this->teamMembers = ArrayHelper::index($this->getTeammemberDatas(), 'id');
        
        $this->cache->set($this->cacheKey, [$this->teams,$this->teamMembers,  time()]);
    }
    /**
     * 获取团队数据
     * @return array(id,name,type,image,index)
     */
    private function getTeamsDatas(){
        $query = (new Query())
                ->select(['id','name','type','image','index'])
                ->from(Team::tableName());
        return $query->all(Yii::$app->db);
    }
    
    /**
     * 获取所有团队成员数据
     * @return arrya (id,team_id,u_id,is_leader,index,position_id,is_delete)
     */
    private function getTeammemberDatas(){
        $query = (new Query())
                ->select(['id','team_id','u_id','is_leader','index','position_id','is_delete'])
                ->from(TeamMember::tableName());
        return $query->all(Yii::$app->db);
    }
    
    /**
     * 获取单例
     * @return TeamMemberTool
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new TeamMemberTool();
        }
        return self::$instance;
    }
}
