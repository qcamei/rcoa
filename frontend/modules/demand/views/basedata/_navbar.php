<?php

use frontend\modules\demand\assets\BasedataAssets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$menus = [
   [
       'name'=>  Yii::t('rcoa/basedata', 'Item Type'),
       'url'=>['/demand/business'],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  Yii::t('rcoa/basedata', 'College'),
       'url'=>['/demand/college'],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  Yii::t('rcoa/basedata', 'Project'),
       'url'=>['/demand/project'],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  Yii::t('rcoa/basedata', 'Course'),
       'url'=>['/demand/course'],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  Yii::t('rcoa/basedata', 'Expert'),
       'url'=>['/demand/expert'],
       'class'=>'btn btn-default',
   ],
];
$controllerId = $controllerId = Yii::$app->controller->id;          //当前控制器);

BasedataAssets::register($this);
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
    <?= Html::a('导入基础数据', Url::to(['/demand/basedata-import/upload']), [
        'class' => 'btn btn-primary'
    ]) ?>
</div>

