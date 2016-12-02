<?php

use frontend\modules\demand\assets\BasedataAssets;
use yii\helpers\Html;
use yii\helpers\Url;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$type = Yii::$app->getRequest()->getQueryParam('navtype');
$menus = [
   [
       'name'=>  Yii::t('rcoa', 'Business'),
       'url'=>['/demand/business','navtype'=>0],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  Yii::t('rcoa', 'Fw College'),
       'url'=>['/demand/college','navtype'=>1],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  Yii::t('rcoa', 'Fw Project'),
       'url'=>['/demand/project','navtype'=>2],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  Yii::t('rcoa', 'Fw Course'),
       'url'=>['/demand/course','navtype'=>3],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  Yii::t('rcoa', 'Expert'),
       'url'=>['/demand/expert','navtype'=>4],
       'class'=>'btn btn-default',
   ],
];
BasedataAssets::register($this);
?>
<div class="container basedata-navbar">
    <div class="btn-group">
        <?php
        foreach ($menus AS $index => $menuItem) {
            echo Html::a($menuItem['name'], Url::to($menuItem['url']), ['class' => $menuItem['class'] . ($type == $index ? ' active' : '')]);
        }
        ?>
    </div>
</div>

