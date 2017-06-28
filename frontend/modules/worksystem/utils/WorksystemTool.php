<?php

namespace frontend\modules\worksystem\utils;


class WorksystemTool 
{
   private static $instance = null;
   
   

    /**
     * 获取单例
     * @return WorksystemTool
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new WorksystemTool();
        }
        return self::$instance;
    }
}
