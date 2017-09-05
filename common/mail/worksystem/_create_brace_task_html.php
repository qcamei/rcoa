<?php

use common\models\worksystem\WorksystemTask;
use yii\helpers\Html;

/**
 * 标题：任务-寻求支撑  
   内容： 
        您好！接收到寻求支撑 ,请及时查看！ 
        课程名称   ： ｛课程名称｝ 
        任务名称   ： ｛课程名称｝ 
        要求完成时间： ｛计划验收时间｝ 
        团队     ： ｛团队名称｝ 
        备注     ： ｛备注｝
  
 * 马上查看(连接到任务详细页) 
 */

 /* @var $model WorksystemTask */

?>

<div class="gray">您好！接收到寻求支撑 ,请及时查看！ </div>
    
<div class="normal">课程名称：<?= Html::encode($model->course->name) ?></div>

<div class="normal">任务名称：<?= Html::encode($model->name) ?></div>

<div class="normal">团队：<?= Html::encode($model->createTeam->name) ?></div>

<div class="highlight">要求完成时间：<?= Html::encode($model->plan_end_time) ?></div>

<div class="normal">备注：<?= Html::encode($des) ?></div>
