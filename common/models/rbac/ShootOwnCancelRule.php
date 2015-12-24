<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\models\rbac;

/**
 * 取消自己的任务
 * 1、必须是自己的任务
 * 2、必须提前24小时取消
 *
 * @author Administrator
 */
class ShootOwnCancelRule extends ShootOwnRule 
{
    public $name = 'ShootOwnCancelRule';
    //put your code here
    public function execute($user, $item, $params)
    {
        $isOwn = parent::execute($user, $item, $params);
        return $isOwn && (isset($params['job']) ? $params['job']->book_time - time()>=24*60*60 : false);
    }
}
