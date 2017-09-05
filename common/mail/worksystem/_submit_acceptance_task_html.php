<?php

use common\models\worksystem\WorksystemTask;
use yii\helpers\Html;

/**
 * 标题：任务-验收申请  
 * 内容： 
        您好！收到任务验收申请 ,请及时查看！ 
        课程名   ： ｛课程名称｝ 
        要求完成时间： ｛计划验收时间｝ 
        制作人   ： ｛制作人名称｝ 
        备注     ：｛备注｝  
 
 * 马上查看(连接到任务详细页) 
 */

 /* @var $model WorksystemTask */

?>
<div class="gray">您好！收到任务验收申请 ,请及时查看！  </div>
    
<div class="normal">课程名称：<?= Html::encode($model->course->name) ?></div>

<div class="normal">任务名称：<?= Html::encode($model->name) ?></div>

<div class="normal">制作人：<?= Html::encode($nickname) ?></div>

<div class="normal">要求完成时间：<?= Html::encode($model->plan_end_time) ?></div>

<div class="highlight">备注：<?= Html::encode($des) ?></div>