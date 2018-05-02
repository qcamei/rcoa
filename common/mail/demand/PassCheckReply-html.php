<?php

use common\models\demand\DemandTask;
use yii\helpers\Html;

/**
 *  您好！任务审核已通过。
    课程名 ：{课程名称}
    计划验收时间 ：{计划验收时间}
    备注 ：{备注}
    马上查看(连接到任务详细页)
 */

 /* @var $model DemandTask */

?>
<div class="gray">您好！任务审核已通过。</div>
    
<div class="normal">课程名称：<?= Html::encode($model->course->name) ?></div>

<div class="normal">计划验收时间：<?= Html::encode($model->plan_check_harvest_time) ?></div>

<div class="highlight">备注：<?= Html::encode($des) ?></div>