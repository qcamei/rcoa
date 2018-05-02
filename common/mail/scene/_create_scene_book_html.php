<?php

use common\models\scene\SceneBook;
use yii\helpers\Html;

/* 标题：拍摄-新增-｛课程名称｝
 * 内容：
    您好！｛预约人｝｛手机｝已经申请了拍摄预约任务。
    地点时间 ：【｛场地｝】｛时间｝
    课程名   ：｛课程名称｝
    备注     ：｛备注｝

    马上查看(连接到任务详细页)
 */

 /* @var $model SceneBook */

?>
<div class="gray">您好！<?= Html::encode($model->booker->nickname) ?> （<?= Html::encode($model->booker->phone) ?>）已经申请了拍摄预约。</div>

<div class="highlight">地点时间：<?= Html::encode("【{$model->sceneSite->name}】".$model->date."　".$model->start_time."（".Yii::t('rcoa', 'Week ' . date('D', strtotime($model->date)))."）") ?></div>

<div class="normal">课程名：<?= Html::encode($model->course->name) ?></div>

<div class="highlight">备注：<?= Html::encode($model->remark) ?></div>