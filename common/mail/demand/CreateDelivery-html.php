<?php

use common\models\demand\DemandDelivery;
use yii\helpers\Html;

/**
 *  您好！任务已提交，请及时查看、验收！ 
    课程名 ：{课程名称}
    承接人 ：{承接人}
    计划验收时间 ：{计划验收时间}
    备注 ：{备注}
    马上查看(连接到任务详细页)
 */

 /* @var $model DemandDelivery */

?>

<div class="mail-new-demand-task">
    
    <p>您好！任务已交付，请及时查看、验收！</p>
    
    <p><b>课程名</b>：<?= Html::encode($model->demandTask->course->name) ?></p>
    
    <p><b>承接人</b>：<?= Html::encode($model->demandTask->undertakePerson->nickname) ?></p>
    
    <p><b>计划验收时间</b>：<?= Html::encode($model->demandTask->plan_check_harvest_time) ?></p>
    
    <p><b>备注</b>：<span style="color:red"><?= Html::encode($model->demandTask->des) ?></span></p>
    
    <?= Html::a('马上查看', 
            Yii::$app->urlManager->createAbsoluteUrl(['/demand/task/view','id' => $model->demand_task_id]), 
            [   
                'class'=>'btn btn-default', 
                'target'=>'_blank'
            ]) ?>
</div>

