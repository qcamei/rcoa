<?php

use common\models\demand\DemandCheckReply;
use yii\helpers\Html;

/**
 *  您好！任务已承接！ 
    课程名 ：{课程名称}
    发布人 ：{发布人}
    计划验收时间 ：{计划验收时间}
    备注 ：{备注}
    马上查看(连接到任务详细页)
 */

 /* @var $model DemandCheckReply */

?>

<div class="mail-new-demand-task">
    
    <p>您好！新任务发布，请及时查看！ </p>
    
    <p><b>课程名</b>：<?= Html::encode($model->demandCheck->demandTask->course->name) ?></p>
    
    <p><b>发布人</b>：<?= Html::encode($model->demandCheck->demandTask->createBy->nickname) ?></p>
    
    <p><b>计划验收时间</b>：<span style="color:red"><?= Html::encode($model->demandCheck->demandTask->plan_check_harvest_time) ?></span></p>
    
    <p><b>备注</b>：<?= Html::encode($model->demandCheck->demandTask->des) ?></p>
    
    <?= Html::a('马上查看', 
            Yii::$app->urlManager->createAbsoluteUrl(['/demand/task/view','id' => $model->demandCheck->demand_task_id]), 
            [   
                'class'=>'btn btn-default', 
                'target'=>'_blank'
            ]) ?>
</div>

