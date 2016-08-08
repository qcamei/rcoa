<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace wskeee\rbac;

use common\models\User;
use yii\caching\Cache;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
/**
 * Description of RbacManager
 *
 * @author Administrator
 */
class RbacManager extends DbManager{
    
    public function init() 
    {
        parent::init();
        $this->loadFromCache();
    }

    /**
     *
     * @var array [parent => list of child]
     */
    protected $childs;
    
    protected $assignmentsCache = [];
    
    public function loadFromCache()
    {
        if ($this->items !== null || !$this->cache instanceof Cache) {
            return;
        }

        $data = $this->cache->get($this->cacheKey);
        
        if (is_array($data) && isset($data[0], $data[1], $data[2])) {
            list ($this->items, $this->rules, $this->parents) = $data;
            return;
        }
        
        $query = (new Query)->from($this->itemTable);
        $this->items = [];
        foreach ($query->all($this->db) as $row) {
            $this->items[$row['name']] = $this->populateItem($row);
        }

        $query = (new Query)->from($this->ruleTable);
        $this->rules = [];
        foreach ($query->all($this->db) as $row) {
            $this->rules[$row['name']] = unserialize($row['data']);
        }

        
        $query = (new Query)->from($this->itemChildTable);
        $this->parents = [];
        foreach ($query->all($this->db) as $row) {
            if (isset($this->items[$row['child']])) {
                $this->parents[$row['child']][] = $row['parent'];
            }
            
            if (isset($this->items[$row['parent']])) {
                $this->childs[$row['parent']][] = $row['child'];
            }
        }
        
        //create roleToUsers;
        //$this->$assignments = [];
        /* 
        $query = (new Query)->from($this->assignmentTable);
        foreach ($query->all($this->db) as $row)
        {
            if(isset($this->items[$row['item_name']]))
                $this->$assignments[$row['user_id']][] = $row['item_name'];
        }*/
        
        $this->cache->set($this->cacheKey, [$this->items, $this->rules, $this->parents]);
    }
    
    /**
     * @inheritdoc
     */
    public function getAssignments($userId)
    {
        if(isset($this->assignmentsCache[$userId]))
            return $this->assignmentsCache[$userId];
        else{
            $assignments = parent::getAssignments($userId);
            $this->assignmentsCache[$userId] = $assignments;
            return $assignments;
        }
    }
    
    /**
     * 获取属于该角色或者权限的所有用户
     * @param string $itemName  目标角色或者权限
     * @param array $result     []
     * @return 最终结果,所甩用户集合
     */
    protected function getItemUser($itemName,$result)
    {
        $atousers = isset($this->assignmentToUsers[$itemName]) ? $this->assignmentToUsers[$itemName] : [];
        $result = array_unique(ArrayHelper::merge($atousers,$result));
        if(isset($this->childs[$itemName]))
        {
            foreach ($this->childs[$itemName] as $child)
                $result = array_unique(ArrayHelper::merge($this->getItemUser($child,$result),$result));
        }
        return $result;
    }

    /**
     * 获取拥有该角色或者权限所有用户，<br/>
     * 比如，获取所有【编导】，或者所有能【创建预约】的用户
     * @param string $itemName  角色或者权限 [User]
     * @return array [User,User]
     */
    public function getItemUsers($itemName)
    {
        $userIds = isset($this->assignmentToUsers[$itemName]) ? $this->assignmentToUsers[$itemName] : [];
        $result = User::findAll(['id'=>  $userIds]);
        
        return $result;
    }
    
    /**
     * 判断用户是否属于{$roseName} 角色
     * @param type $roleName    目标角色
     * @param type $userId      目标id
     * @return boolean 
     */
    public function isRole($roleName,$userId)
    {
        return $this->checkAccess($userId, $roleName);
    }
}
