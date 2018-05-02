<?php

use common\models\need\NeedTask;
use yii\helpers\Html;

/* 标题：需求任务-验收反馈
    内容：
        您好！您的需求验收 {通过/不通过} ,请及时查看！
    课程名称    ：｛课程名称｝
    需求名称    ：｛需求名称｝
    需求时间    ：｛需求时间｝
    验收人      ：｛验收人｝
    验收时间    ：｛请求时间｝
    备注        ：｛备注｝



    马上查看(连接到任务详细页)
 */

 /* @var $model NeedTask */

?>
<div class="gray">您好！你的需求验收【<?= $results['result'] ? '通过' : '不通过' ?>】,请及时查看！</div>

<div class="normal">课程名称：<?= Html::encode($model->course->name) ?></div>

<div class="normal">需求名称：<?= Html::encode($model->task_name) ?></div>

<div class="normal">需求时间：<?= Html::encode(date('Y-m-d H:i', $model->need_time)) ?></div>

<div class="normal">验收人：<?= Html::encode($model->createdBy->nickname) ?></div>

<div class="normal">验收时间：<?= Html::encode(date('Y-m-d H:i')) ?></div>

<div class="highlight">备注：<?= Html::encode($results['remarks']) ?></div>