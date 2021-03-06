<?php

use common\models\scene\SceneBook;
use yii\helpers\Html;

/* 标题：拍摄-申请转让
 * 内容：
   您好！现有拍摄预约任务申请转让，有需要请预约。
   地点时间：【｛场地｝】｛时间｝
   原因    ：｛转让原因｝

   马上查看(连接到任务详细页)
 */

 /* @var $model SceneBook */

?>
<div class="gray">您好！现有拍摄预约任务申请转让，有需要请预约。</div>

<div class="normal">地点时间：<?= Html::encode("【{$model->sceneSite->name}】".$model->date."　".$model->start_time."（".Yii::t('rcoa', 'Week ' . date('D', strtotime($model->date)))."）") ?></div>

<div class="highlight">原因：<?= Html::encode($content) ?></div>