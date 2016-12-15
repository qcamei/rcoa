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
}