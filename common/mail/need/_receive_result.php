<?php

use common\models\need\NeedTask;
use yii\helpers\Html;

/* 标题：需求任务-承接反馈
    内容：
        您好！你的需求任务已被承接 ,请及时查看！
    课程名称    ：｛课程名称｝
    需求名称    ：｛需求名称｝
    需求时间    ：｛需求时间｝
    承接人      ：｛承接人｝
    承接时间    ：｛请求时间｝



    马上查看(连接到任务详细页)
 */

 /* @var $model NeedTask */

?>
<div class="gray">您好！你的需求任务已被承接 ,请及时查看！</div>

<div class="normal">课程名称：<?= Html::encode($model->course->name) ?></div>

<div class="normal">需求名称：<?= Html::encode($model->task_name) ?></div>

<div class="highlight">需求时间：<?= Html::encode(date('Y-m-d H:i', $model->need_time)) ?></div>

<div class="normal">承接人：<?= Html::encode($model->receiveBy->nickname) ?></div>

<div class="normal">承接时间：<?= Html::encode(date('Y-m-d H:i')) ?></div>