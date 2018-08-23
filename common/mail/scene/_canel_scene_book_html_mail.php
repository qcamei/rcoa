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
<style>
    div {margin: 20px 0;}
    .gray {color: #999999;}
    .normal {color: #333333;}
    .highlight {color: #f75830;}
    .title {color: #999;font-weight: bold;}
    .link-btn{display: inline-block;padding: 10px 20px;background-color: #FF6600;color: #FFF}
</style>
<div class="gray">您好！该拍摄预约已取消，请知悉。</div>

<div class="highlight">
    <span class="title">地点时间：</span>
    <?= Html::encode("【{$model->sceneSite->name}】".$model->date.SceneBook::$timeIndexMap[$model->time_index].$model->start_time."(".Yii::t('rcoa', 'Week ' . date('D', strtotime($model->date))).")") ?></div>

<div class="normal">
    <span class="title">课程名：</span>
    <?= Html::encode($model->course->name) ?></div>

<div class="highlight">
    <span class="title">原因：</span>
    <?= Html::encode($content) ?></div>

<a href="<?=$link?>" target="_black"><span class="link-btn">点击查看</span></a>