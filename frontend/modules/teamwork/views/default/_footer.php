<?php

use yii\bootstrap\NavBar;
use yii\helpers\Html;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="controlbar" style="height: 60px;padding-top:0px; ">
    <div class="container">
        <?= Html::a(Html::img(['/filedata/image/home_64x64.png']).'主页', 'javascript:;', ['id'=>'submit', 'class' => 'footer-item',]) ?>
        <?= Html::a(Html::img(['/filedata/image/project_64x64.png']).'项目', ['type', 'id' => ''], ['class' => 'footer-item']) ?>
        <?= Html::a(Html::img(['/filedata/image/course_64x64.png']).'课程', ['type', 'id' => ''], ['class' => 'footer-item']) ?>
        <?= Html::a(Html::img(['/filedata/image/statistics_64x64.png']).'统计', ['type', 'id' => ''], ['class' => 'footer-item']) ?>
        <?= Html::a(Html::img(['/filedata/image/new_64px64.png']).'创建项目', ['type', 'id' => ''], ['class' => 'footer-item footer-item-right']) ?>
        
    </div>
</div>
