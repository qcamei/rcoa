<?php
namespace common\config;

use common\models\System;
use Yii;

class AppGlobalVariables{
    
    /** @var  System  $_system */
    private static $_system = null;
  
    /**
     * 获取单条系统数据
     * @return System
     */
    public static function getSystem()
    {   
        if(self::$_system == null){
            self::$_system = System::findOne(['aliases'=> Yii::$app->controller->module->id]);
        }
        
        return self::$_system;
    }
    
    /**
     * 获取所有系统数据
     * @return System
     */
    public static function getSystems()
    {   
        return System::find()->where(['is_delete' => 'N'])->orderBy('index asc')->all();
    }
    
    /**
     * 获取系统ID
     * @return type
     */
    public static function getSystemId()
    {
        $system = self::getSystem();
        return $system->id;
    }
    
    /**
     * 组装菜单导航
     */
    public static function __getSystems(){
        $system = self::getSystems();
        $menuItems = [];
        foreach($system as $_system){
            if($_system->parent_id == null){
                $children = self::getNavItemChildren($system, $_system->id);
                $item = [
                    'label'=> $_system->name,
                ];
                if(count($children)>0){
                    $item['items'] = $children;
                    $item['url'] = ["/{$_system->aliases}"];
                }else
                    $item['url'] = [$_system->module_link];
                $item['aliases'] = $_system->aliases;    
                $menuItems[] = $item;
            }
        }
        return $menuItems;
        
    }

    /**
     * 获取二级导航
     * @param array $allSystems           获取所有导航
     * @param integer $parent_id          父级ID
     * @return array
     */
    private static function getNavItemChildren($allSystems, $parent_id){
        $items = [];
        foreach($allSystems as $systme){
            if($systme->parent_id == $parent_id){
                $items[]=[
                    'label'=> $systme->name,
                    'url'=> [$systme->module_link],
                    'aliases' => $systme->aliases,
                ];
            }
        }
        return $items;
    }
}