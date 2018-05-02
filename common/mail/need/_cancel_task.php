<?php

use common\models\need\NeedTask;
use yii\helpers\Html;

/* 标题：需求任务-需求取消
    内容：
        您好！需求任务已取消 ,请及时查看！
    课程名称    ：｛课程名称｝
    需求名称    ：｛需求名称｝
    需求时间    ：｛需求时间｝
    取消人      ：｛取消人｝
    取消时间    ：｛请求时间｝
    备注        ：｛备注｝

    马上查看(连接到任务详细页)
 */

 /* @var $model NeedTask */

?>
<div class="gray">您好！你的需求任务已被承接 ,请及时查看！</div>

<div class="normal">课程名称：<?= Html::encode($model->course->name) ?></div>

<div class="normal">需求名称：<?= Html::encode($model->task_name) ?></div>

<div class="normal">需求时间：<?= Html::encode(date('Y-m-d H:i', $model->need_time)) ?></div>

<div class="normal">取消人：<?= Html::encode(Yii::$app->user->identity->nickname) ?></div>

<div class="normal">取消时间：<?= Html::encode(date('Y-m-d H:i')) ?></div>
s
<div class="highlight">备注：<?= Html::encode($results['remarks']) ?></div>