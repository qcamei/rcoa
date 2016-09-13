<?php

use common\models\multimedia\CourseManage;
use frontend\modules\multimedia\MultimediaAsset;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="controlbar footer-multimedia-navbar" style="height: 50px;padding-top:0px; ">
    <div class="container">
        <?php
            $controllerId = Yii::$app->controller->id;          //当前控制器
            $actionId = Yii::$app->controller->action->id;      //当前行为方法
            /** 小屏幕显示 */
            echo Html::a(Html::img(['/filedata/multimedia/image/home.png']), ['index'], 
                    ['class' => $actionId == 'index' 
                    ? 'footer-multimedia-xs footer-multimedia-bg visible-xs-inline-block' : 'footer-multimedia-xs visible-xs-inline-block']);
            
            echo Html::a(Html::img(['/filedata/multimedia/image/personal.png']), ['personal'],  
                    ['class' => $actionId == 'personal'? 
                        'footer-multimedia-xs footer-multimedia-bg visible-xs-inline-block' : 'footer-multimedia-xs visible-xs-inline-block']);
            
            echo Html::a(Html::img(['/filedata/multimedia/image/team.png']), ['team'], [
                    'class' => $actionId == 'team'? 
                        'footer-multimedia-xs footer-multimedia-bg visible-xs-inline-block' : 'footer-multimedia-xs visible-xs-inline-block']);
            
            echo Html::a(Html::img(['/filedata/multimedia/image/statistics.png']), ['/multimedia/statistics'], 
                    ['class' => $controllerId == 'statistics' ? 'footer-multimedia-xs footer-multimedia-bg visible-xs-inline-block' : 'footer-multimedia-xs visible-xs-inline-block']);
            /**
             * 创建项目 按钮必须满足以下条件：
             * 1、操作方法必须是 【list】
             * 2、必须是【队长】 or 【项目管理员】
             */
            if($actionId == 'personal' /*&& ($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER))*/)
                echo Html::a(Html::img(['/filedata/multimedia/image/create.png']), ['create'], [
                    'class' => 'footer-multimedia-xs footer-multimedia-right visible-xs-inline-block']);
            
            
            /** 大屏幕上显示 */
            echo Html::a(Html::img(['/filedata/multimedia/image/home.png']).'主页', ['index'], 
                    ['class' => $actionId == 'index' 
                    ? 'footer-multimedia footer-multimedia-bg hidden-xs' : 'footer-multimedia hidden-xs']);
            
            echo Html::a(Html::img(['/filedata/multimedia/image/personal.png']).'个人', ['personal'],  
                    ['class' => $actionId == 'personal' ? 
                        'footer-multimedia footer-multimedia-bg hidden-xs' : 'footer-multimedia hidden-xs']);
            
            echo Html::a(Html::img(['/filedata/multimedia/image/team.png']).'团队', ['team'], [
                    'class' =>  $actionId == 'team' ? 
                        'footer-multimedia footer-multimedia-bg hidden-xs' : 'footer-multimedia hidden-xs']);
            
            echo Html::a(Html::img(['/filedata/multimedia/image/statistics.png']).'统计', ['/multimedia/statistics'], 
                    ['class' => $controllerId == 'statistics' ? 'footer-multimedia footer-multimedia-bg hidden-xs' : 'footer-multimedia hidden-xs']);
            /**
             * 创建项目 按钮必须满足以下条件：
             * 1、操作方法必须是 【list】
             * 2、必须是【队长】 or 【项目管理员】
             */
            if($actionId == 'personal' /*&& ($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER))*/)
                echo Html::a(Html::img(['/filedata/multimedia/image/create.png']).'创建任务', ['create'], [
                    'class' => 'footer-multimedia footer-multimedia-right hidden-xs']);
        ?>
    </div>
</div>

<?php
    MultimediaAsset::register($this);
?>
