<?php

use common\models\scene\SceneBook;
use yii\helpers\Html;

/* 标题：拍摄-更改指派-｛课程名称｝
 * 内容：
   您好！｛课程名称｝拍摄预约任务已经重新安排了摄影师。
   原因     ：｛更改指派的原因｝
   地点时间 ：【｛场地｝】｛时间｝
   旧摄影师 ：｛摄影师｝｛手机｝
   新摄影师 ：｛摄影师｝｛手机｝
   备注     ：｛备注｝

   马上查看(连接到任务详细页)
 */

 /* @var $model SceneBook */

?>
<div class="gray">您好！【<?= Html::encode($model->course->name) ?>】拍摄预约任务已经重新安排了摄影师。</div>

<div class="normal">地点时间：<?= Html::encode("【{$model->sceneSite->name}】".$model->date."　".$model->start_time."（".Yii::t('rcoa', 'Week ' . date('D', strtotime($model->date)))."）") ?></div>

<div class="normal">旧摄影师：<?= Html::encode($oldShootMan) ?></div>

<div class="highlight">新摄影师：<?= Html::encode($newShootMan) ?></div>

