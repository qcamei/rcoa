<?php

use common\models\demand\DemandTask;
use yii\helpers\Html;

/**
 *  您好！任务已承接！  
    课程名 ：{课程名称}
    承接人 ：{承接人}
    计划验收时间 ：{计划验收时间}
    备注 ：{备注}
    马上查看(连接到任务详细页)
 */

 /* @var $model DemandTask */

?>
<div class="gray">您好！任务已承接！ </div>

<div class="normal">课程名称：<?= Html::encode($model->course->name) ?></div>

<div class="normal">承接人：<?= Html::encode($model->undertakePerson->nickname) ?></div>

<div class="highlight">计划验收时间：<?= Html::encode($model->plan_check_harvest_time) ?></div>

<div class="normal">备注：<?= Html::encode($des) ?></div>