<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$team_id = !is_array($twTool->getHotelTeam(Yii::$app->user->id)) ? $twTool->getHotelTeam(Yii::$app->user->id) : ''
?>

<div class="controlbar footer-item-list" style="height: 50px;padding-top:0px; ">
    <div class="container">
        <?php
            $controllerId = Yii::$app->controller->id;          //当前控制器
            $actionId = Yii::$app->controller->action->id;      //当前行为方法
            /** 小屏幕显示 */
            echo Html::a(Html::img(['/filedata/teamwork/image/home.png']), ['default/index'], 
                    ['class' => $controllerId == 'default' &&  $actionId == 'index' 
                    ? 'footer-item-xs footer-item-bg visible-xs-inline-block' : 'footer-item-xs visible-xs-inline-block']);
            
            /*echo Html::a(Html::img(['/filedata/teamwork/image/project.png']), ['default/list'],  
                    ['class' => $controllerId == 'default' && ($actionId == 'list' || $actionId == 'search') ? 
                        'footer-item-xs footer-item-bg visible-xs-inline-block' : 'footer-item-xs visible-xs-inline-block']);*/
            
            echo Html::a(Html::img(['/filedata/teamwork/image/course.png']), ['course/index',
                    'team_id' => $team_id,
                    'status' => CourseManage::STATUS_NORMAL], [
                    'class' => $controllerId == 'course' && $actionId == 'index' ? 
                        'footer-item-xs footer-item-bg visible-xs-inline-block' : 'footer-item-xs visible-xs-inline-block']);
            
            echo Html::a(Html::img(['/filedata/teamwork/image/statistics.png']), ['/teamwork/statistics'], 
                    ['class' => $controllerId == 'statistics' ? 'footer-item-xs footer-item-bg visible-xs-inline-block' : 'footer-item-xs visible-xs-inline-block']);
            /**
             * 创建项目 按钮必须满足以下条件：
             * 1、操作方法必须是 【list】
             * 2、必须是【队长】 or 【项目管理员】
             
            if($actionId == 'list' && ($twTool->getIsAuthority('is_leader', 'Y') || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
                echo Html::a(Html::img(['/filedata/teamwork/image/create.png']), ['create'], [
                    'class' => 'footer-item-xs footer-item-right visible-xs-inline-block']);*/
            
            /** 大屏幕上显示 */
            echo Html::a(Html::img(['/filedata/teamwork/image/home.png']).'主页', ['default/index'], 
                    ['class' => $controllerId == 'default' &&  $actionId == 'index' 
                    ? 'footer-item footer-item-bg hidden-xs' : 'footer-item hidden-xs']);
            
            /*echo Html::a(Html::img(['/filedata/teamwork/image/project.png']).'项目', ['default/list'],  
                    ['class' => $controllerId == 'default' && ($actionId == 'list' || $actionId == 'search') ? 
                        'footer-item footer-item-bg hidden-xs' : 'footer-item hidden-xs']);*/
            
            echo Html::a(Html::img(['/filedata/teamwork/image/course.png']).'课程', ['course/index', 
                    'team_id' => $team_id, 
                    'status' => CourseManage::STATUS_NORMAL], [
                    'class' =>  $controllerId == 'course' && $actionId == 'index' ? 
                        'footer-item footer-item-bg hidden-xs' : 'footer-item hidden-xs']);
            
            echo Html::a(Html::img(['/filedata/teamwork/image/statistics.png']).'统计', ['/teamwork/statistics'], 
                    ['class' => $controllerId == 'statistics' ? 'footer-item footer-item-bg hidden-xs' : 'footer-item hidden-xs']);
            /**
             * 创建项目 按钮必须满足以下条件：
             * 1、操作方法必须是 【list】
             * 2、必须是【队长】 or 【项目管理员】
             
            if($actionId == 'list' && ($twTool->getIsAuthority('is_leader', 'Y') || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
                echo Html::a(Html::img(['/filedata/teamwork/image/create.png']).'创建项目', ['create'], [
                    'class' => 'footer-item footer-item-right hidden-xs']);*/
        ?>
    </div>
</div>

<?php
    TwAsset::register($this);
?>
