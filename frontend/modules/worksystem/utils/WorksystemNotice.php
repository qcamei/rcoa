<?php

namespace frontend\modules\worksystem\utils;


class WorksystemNotice 
{
    private static $instance = null;
    
    
    
    /**
     * 获取单例
     * @return WorksystemNotice
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new WorksystemNotice();
        }
        return self::$instance;
    }
}