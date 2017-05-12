<?php

use common\models\demand\DemandAppeal;
use yii\helpers\Html;

/**
 *  您好！任务正在申诉中，请及时查看！ 
    课程名 ：{课程名称}
    申诉人 ：{申诉人}
    计划验收时间 ：{计划验收时间}
    备注 ：{备注}
    马上查看(连接到任务详细页)
 */

 /* @var $model DemandAppeal */

?>

<div class="mail-new-demand-task">
    
    <p>您好！任务正在申诉中，请及时查看！</p>
    
    <p><b>课程名</b>：<?= Html::encode($model->demandTask->course->name) ?></p>
    
    <p><b>申诉人</b>：<?= Html::encode($model->createBy->nickname) ?></p>
    
    <p><b>计划验收时间</b>：<?= Html::encode($model->demandTask->plan_check_harvest_time) ?></p>
    
    <p><b>备注</b>：<span style="color:red"><?= Html::encode($model->reason) ?></span></p>
    
    <?= Html::a('马上查看', 
            Yii::$app->urlManager->createAbsoluteUrl(['/demand/task/view','id' => $model->demand_task_id]), 
            [   
                'class'=>'btn btn-default', 
                'target'=>'_blank'
            ]) ?>
</div>

