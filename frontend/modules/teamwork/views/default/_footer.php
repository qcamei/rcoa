<?php

use frontend\modules\teamwork\TwAsset;
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
            
            if($actionId == 'list')
                echo Html::a(Html::img(['/filedata/image/new_64px64.png']), ['create'], [
                    'class' => $twTool->getIsLeader() ? 'footer-item-xs footer-item-right visible-xs-inline-block' : 'footer-item-xs footer-item-right disabled visible-xs-inline-block']);
            
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
            
            if($actionId == 'list')
                echo Html::a(Html::img(['/filedata/image/new_64px64.png']).'创建项目', ['create'], [
                    'class' => $twTool->getIsLeader() ? 'footer-item footer-item-right hidden-xs' : 'footer-item footer-item-right disabled hidden-xs']);
        ?>
    </div>
</div>

<?php
    TwAsset::register($this);
?>
