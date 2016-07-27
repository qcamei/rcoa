<?php

use frontend\modules\teamwork\TwAsset;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="controlbar footer-item-list" style="height: 60px;padding-top:0px; ">
    <div class="container">
        <?php
            $controllerId = Yii::$app->controller->id;          //当前控制器
            $actionId = Yii::$app->controller->action->id;      //当前行为方法
            /** 小屏幕显示 */
            echo Html::a(Html::img(['/filedata/image/home_64x64.png']), ['default/index'], 
                    ['class' => $controllerId == 'default' &&  $actionId == 'index' 
                    ? 'footer-item-xs footer-item-bg visible-xs-inline-block' : 'footer-item-xs visible-xs-inline-block']);
            
            echo Html::a(Html::img(['/filedata/image/project_64x64.png']), ['default/list'],  
                    ['class' => $actionId == 'list' ? 'footer-item-xs footer-item-bg visible-xs-inline-block' : 'footer-item-xs visible-xs-inline-block']);
            
            echo Html::a(Html::img(['/filedata/image/course_64x64.png']), ['course/index'], [
                    'class' => $controllerId == 'course' ? 'footer-item-xs footer-item-bg visible-xs-inline-block' : 'footer-item-xs visible-xs-inline-block']);
            
            echo Html::a(Html::img(['/filedata/image/statistics_64x64.png']), ['/teamwork/statistics'], 
                    ['class' => $controllerId == 'statistics' ? 'footer-item-xs footer-item-bg visible-xs-inline-block' : 'footer-item-xs visible-xs-inline-block']);
            
            /**
             * 创建项目 按钮必须满足以下条件：
             * 1、操作方法必须是 【list】
             * 2、必须是【队长】 or 【项目管理员】
             */
            if($actionId == 'list' && ($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
                echo Html::a(Html::img(['/filedata/image/new_64px64.png']), ['create'], [
                    'class' => 'footer-item-xs footer-item-right visible-xs-inline-block']);
            
            /** 大屏幕上显示 */
            echo Html::a(Html::img(['/filedata/image/home_64x64.png']).'主页', ['default/index'], 
                    ['class' => $controllerId == 'default' &&  $actionId == 'index' 
                    ? 'footer-item footer-item-bg hidden-xs' : 'footer-item hidden-xs']);
            
            echo Html::a(Html::img(['/filedata/image/project_64x64.png']).'项目', ['default/list'],  
                    ['class' => $actionId == 'list' ? 'footer-item footer-item-bg hidden-xs' : 'footer-item hidden-xs']);
            
            echo Html::a(Html::img(['/filedata/image/course_64x64.png']).'课程', ['course/index'], [
                    'class' => $controllerId == 'course' ? 'footer-item footer-item-bg hidden-xs' : 'footer-item hidden-xs']);
            
            echo Html::a(Html::img(['/filedata/image/statistics_64x64.png']).'统计', ['/teamwork/statistics'], 
                    ['class' => $controllerId == 'statistics' ? 'footer-item footer-item-bg hidden-xs' : 'footer-item hidden-xs']);
            
            /**
             * 创建项目 按钮必须满足以下条件：
             * 1、操作方法必须是 【list】
             * 2、必须是【队长】 or 【项目管理员】
             */
            if($actionId == 'list' && ($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
                echo Html::a(Html::img(['/filedata/image/new_64px64.png']).'创建项目', ['create'], [
                    'class' => 'footer-item footer-item-right hidden-xs']);
        ?>
    </div>
</div>

<?php
    TwAsset::register($this);
?>
