<?php

use common\models\scene\SceneBook;
use yii\helpers\Html;

/* 标题：拍摄-取消预约
 * 内容：
   您好！该拍摄预约已取消，请知悉。
   地点时间：【｛场地｝】｛时间｝
   原因    ：｛取消原因｝

   马上查看(连接到任务详细页)
 */

 /* @var $model SceneBook */

?>
<div class="gray">您好！该拍摄预约已取消，请知悉。</div>

<div class="normal">地点时间：<?= Html::encode("【{$model->sceneSite->name}】".$model->date.SceneBook::$timeIndexMap[$model->time_index].$model->start_time."（".Yii::t('rcoa', 'Week ' . date('D', strtotime($model->date)))."）") ?></div>

<div class="highlight">原因：<?= Html::encode($content) ?></div>