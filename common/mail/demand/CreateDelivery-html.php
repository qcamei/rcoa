<?php

use common\models\demand\DemandTask;
use yii\helpers\Html;

/**
 *  您好！任务已提交，请及时查看、验收！ | 您好！任务已修改完成，请及时查看、验收！
    课程名 ：{课程名称}
    承接人 ：{承接人}
    计划验收时间 ：{计划验收时间}
    备注 ：{备注}
    马上查看(连接到任务详细页)
 */

 /* @var $model DemandTask */

?>
<?php if($model->status == DemandTask::STATUS_UPDATEING): ?>
<div class="gray">您好！任务已修改完成，请及时查看、验收！</div>
<?php else: ?>
<div class="gray">您好！任务已承接！ </div>
<?php endif; ?>

<div class="normal">课程名称：<?= Html::encode($model->course->name) ?></div>

<div class="normal">承接人：<?= Html::encode($model->undertakePerson->nickname) ?></div>

<div class="normal">计划验收时间：<?= Html::encode($model->plan_check_harvest_time) ?></div>

<div class="highlight">备注：<?= Html::encode($des) ?></div>