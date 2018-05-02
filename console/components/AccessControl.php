<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace console\components;

use yii\base\ActionFilter;

/**
 * Description of AccessControl
 *
 * @author Administrator
 */
class AccessControl extends ActionFilter {
    
    public $allowActions = [];
    
    //put your code here
    public function beforeAction($action){
        return true;
    }
}
