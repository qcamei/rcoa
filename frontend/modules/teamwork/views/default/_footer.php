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
            echo Html::a(Html::img(['/filedata/image/home_64x64.png']).'主页', ['default/index'], 
                    ['class' => $controllerId == 'default' &&  $actionId == 'index' 
                    ? 'footer-item footer-item-bg' : 'footer-item']);
            
            echo Html::a(Html::img(['/filedata/image/project_64x64.png']).'项目', ['default/list'],  
                    ['class' => $actionId == 'list' ? 'footer-item footer-item-bg' : 'footer-item']);
            
            echo Html::a(Html::img(['/filedata/image/course_64x64.png']).'课程', ['course/index'], [
                    'class' => $controllerId == 'course' ? 'footer-item footer-item-bg' : 'footer-item']);
            
            echo Html::a(Html::img(['/filedata/image/statistics_64x64.png']).'统计', ['default/statistics'], 
                    ['class' => $actionId == 'statistics' ? 'footer-item footer-item-bg' : 'footer-item']);
            
            if($actionId == 'list')
                echo Html::a(Html::img(['/filedata/image/new_64px64.png']).'创建项目', ['create'], [
                    'class' => $twTool->getIsLeader() ? 'footer-item footer-item-right' : 'footer-item footer-item-right disabled']);
        ?>
    </div>
</div>

<?php
    TwAsset::register($this);
?>
