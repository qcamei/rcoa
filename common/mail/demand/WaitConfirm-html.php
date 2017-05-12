<?php

use common\models\demand\DemandAcceptance;
use yii\helpers\Html;

/**
 *  您好！任务已修改完成，请及时查看、验收！ 
    课程名 ：{课程名称}
    承接人 ：{承接人}
    计划验收时间 ：{计划验收时间}
    备注 ：{备注}
    马上查看(连接到任务详细页)
 */

 /* @var $model DemandAcceptance */

?>

<div class="mail-new-demand-task">
    
    <p>您好！任务已验收通过，请及时查看、确定！</p>
    
    <p><b>课程名</b>：<?= Html::encode($model->demandTask->course->name) ?></p>
    
    <p><b>发布人</b>：<?= Html::encode($model->demandTask->createBy->nickname) ?></p>
    
    <p><b>计划验收时间</b>：<span style="color:red"><?= Html::encode($model->demandTask->plan_check_harvest_time) ?></span></p>
    
    <p><b>备注</b>：<?= Html::encode($model->des) ?></p>
    
    <?= Html::a('马上查看', 
            Yii::$app->urlManager->createAbsoluteUrl(['/demand/task/view','id' => $model->demand_task_id]), 
            [   
                'class'=>'btn btn-default', 
                'target'=>'_blank'
            ]) ?>
</div>

