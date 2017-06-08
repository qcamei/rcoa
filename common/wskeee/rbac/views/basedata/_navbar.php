<?php

use wskeee\rbac\RbacAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$menus = [
   [
       'name'=>  '用户角色',
       'url'=>['/rbac/user-role'],
       'class'=>'btn btn-default',
   ],
   [
       'name'=> '角色管理',
       'url'=>['/rbac/role-manager'],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  '权限管理',
       'url'=>['/rbac/permission-manager'],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  '更新角色与权限',
       'url'=>['/rbac/default'],
       'class'=>'btn btn-default',
   ],
];
$controllerId = $controllerId = Yii::$app->controller->id;          //当前控制器);

RbacAsset::register($this);
?>
<div class="basedata-navbar">
    <div class="btn-group">
        <?php
        foreach ($menus AS $index => $menuItem) {
            $active = strpos($menuItem['url'][0], $controllerId)>0 ? ' active' : '';
            echo Html::a($menuItem['name'], Url::to($menuItem['url']), ['class' => $menuItem['class'].$active ]);
        }
        ?>
    </div>
</div>

