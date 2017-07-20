<?php

use common\models\worksystem\WorksystemTask;
use yii\helpers\Html;

/**
 * 标题：任务-审核申请结果 
   内容： 
        您好！审核申请结果是 不通过 ,请及时查看！ 
        课程名称   ： ｛课程名称｝ 
        任务名称   ： ｛课程名称｝ 
        要求完成时间： ｛计划验收时间｝ 
        审核人   ： ｛审核人名称｝ 
        备注     ： ｛备注｝
  
 * 马上查看(连接到任务详细页) 
 */

 /* @var $model WorksystemTask */

?>

<div class="mail-new-demand-task">
    
    <p>您好！审核申请结果是 不通过 ,请及时查看！ </p>
    
    <p><b>课程名称</b>：<?= Html::encode($model->course->name) ?></p>
    
    <p><b>任务名称</b>：<?= Html::encode($model->name) ?></p>
    
    <p><b>审核人</b>：<?= Html::encode($nickname) ?></p>
    
    <p><b>要求完成时间</b>：<?= Html::encode($model->plan_end_time) ?></p>
    
    <p><b>备注</b>：<span style="color:red"><?= Html::encode($des) ?></span></p>
    
    <?= Html::a('马上查看', 
            Yii::$app->urlManager->createAbsoluteUrl(['/worksystem/task/view','id' => $model->id]), 
            [   
                'class'=>'btn btn-default', 
                'target'=>'_blank'
            ]) ?>
</div>