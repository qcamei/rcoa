<?php

use frontend\modules\multimedia\MultimediaAsset;
use frontend\modules\multimedia\MultimediaTool;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

/* @var $multimedia MultimediaTool */
?>

<div class="controlbar footer-multimedia-navbar" style="height: 50px;padding-top:0px; ">
    <div class="container">
        <?php
            $controllerId = Yii::$app->controller->id;          //当前控制器
            $actionId = Yii::$app->controller->action->id;      //当前行为方法
            /** 小屏幕显示 */
            echo Html::a(Html::img(['/filedata/multimedia/image/home.png']), ['/multimedia/home'], 
                    ['class' => $actionId == 'index' 
                    ? 'footer-multimedia-xs footer-multimedia-bg visible-xs-inline-block' : 'footer-multimedia-xs visible-xs-inline-block']);
            
            echo Html::a(Html::img(['/filedata/multimedia/image/personal.png']), [
                'default/list', 'personal', 'create_by' => Yii::$app->user->id, 'producer' => Yii::$app->user->id, 'assignPerson' => Yii::$app->user->id],  
                    ['class' => $actionId == 'list'? 
                        'footer-multimedia-xs footer-multimedia-bg visible-xs-inline-block' : 'footer-multimedia-xs visible-xs-inline-block']);
            
            echo Html::a(Html::img(['/filedata/multimedia/image/statistics.png']), ['/multimedia/statistics'], 
                    ['class' => $controllerId == 'statistics' ? 'footer-multimedia-xs footer-multimedia-bg visible-xs-inline-block' : 'footer-multimedia-xs visible-xs-inline-block']);
            /**
             * 创建任务 按钮必须满足以下条件：
             * 1、操作方法必须是 【list】
             * 2、必须拥有创建权限
             */
            if($actionId == 'list' && Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CREATE))
                echo Html::a(Html::img(['/filedata/multimedia/image/create.png']), ['create'], [
                    'class' => 'footer-multimedia-xs footer-multimedia-right visible-xs-inline-block']);
            
            
            /** 大屏幕上显示 */
            echo Html::a(Html::img(['/filedata/multimedia/image/home.png']).'主页', ['/multimedia/home'], 
                    ['class' => $actionId == 'index' 
                    ? 'footer-multimedia footer-multimedia-bg hidden-xs' : 'footer-multimedia hidden-xs']);
            
            echo Html::a(Html::img(['/filedata/multimedia/image/personal.png']).'任务', [
                'default/list', 'create_by' => Yii::$app->user->id, 'producer' => Yii::$app->user->id, 'assignPerson' => Yii::$app->user->id],  
                    ['class' => $actionId == 'list' ? 
                        'footer-multimedia footer-multimedia-bg hidden-xs' : 'footer-multimedia hidden-xs']);
            
            echo Html::a(Html::img(['/filedata/multimedia/image/statistics.png']).'统计', ['/multimedia/statistics'], 
                    ['class' => $controllerId == 'statistics' ? 'footer-multimedia footer-multimedia-bg hidden-xs' : 'footer-multimedia hidden-xs']);
            /**
             * 创建任务 按钮必须满足以下条件：
             * 1、操作方法必须是 【list】
             * 2、必须拥有创建权限
             */
            if($actionId == 'list' && Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CREATE))
                echo Html::a(Html::img(['/filedata/multimedia/image/create.png']).'创建任务', ['create'], [
                    'class' => 'footer-multimedia footer-multimedia-right hidden-xs']);
        ?>
    </div>
</div>

<?php
    MultimediaAsset::register($this);
?>
