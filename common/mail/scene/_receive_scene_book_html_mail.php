<?php

use common\models\scene\SceneBook;
use yii\helpers\Html;

/* 标题：拍摄-预约转让
 * 内容：
   您好！拍摄 预约转让 成功，请及时查看。
   地点时间：【｛场地｝】｛时间｝
   旧预约人：李小红（123123123）
   新预约人：红小李（123123123）
   备注     ：｛备注｝

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
<div class="gray">您好！拍摄 预约转让成功，请及时查看。</div>

<div class="normal">地点时间：<?= Html::encode("【{$model->sceneSite->name}】".$model->date."　".$model->start_time."（".Yii::t('rcoa', 'Week ' . date('D', strtotime($model->date)))."）") ?></div>

<div class="normal">旧预约人：<?= Html::encode($oldBookerName) ?></div>

<div class="normal">新预约人：<?= Html::encode($model->booker->nickname."（{$model->booker->phone}）") ?></div>

<div class="highlight">备注：<?= Html::encode($model->remark) ?></div>