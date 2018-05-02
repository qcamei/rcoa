<?php

use common\models\demand\DemandTask;
use yii\helpers\Html;

/**
 *  您好！新任务发布，请及时查看、审核。
    课程名 ：{课程名称}
    发布人 ：{发布人}
    计划验收时间 ：{计划验收时间}
    备注 ：{备注}
    马上查看(连接到任务详细页)
 */

 /* @var $model DemandTask */

?>
<?php if($model->status == DemandTask::STATUS_DEFAULT):?>
<div class="gray">您好！新任务发布，请及时查看、审核。  </div>
<?php else: ?>
<div class="gray">您好！任务已重新调整，请及时查看、审核。 </div>
<?php endif; ?>
<div class="normal">课程名称：<?= Html::encode($model->course->name) ?></div>

<div class="normal">发布人：<?= Html::encode($model->createBy->nickname) ?></div>

<div class="highlight">计划验收时间：<?= Html::encode($model->plan_check_harvest_time) ?></div>

<div class="normal">备注：<?= Html::encode($des) ?></div>
