<?php

use common\models\scene\SceneBook;
use yii\helpers\Html;

/**
 *  标题：拍摄-即将失约
    内容：
    您好！拍摄任务 即将失约，请及时查看。
    地点时间：【｛场地｝】｛时间｝
    课程    ：｛课程名称｝
    预约人  ：｛预约人｝

    备注    ：｛备注｝

        马上查看(连接到任务详细页)
*/

?>
<div class="gray">您好！拍摄任务 【即将失约】 请及时查看。</div>
    
<div class="normal">地点时间：<?= Html::encode("【{$book['site_name']}】".$book['date']."　".$book['start_time']."（".Yii::t('rcoa', 'Week ' . date('D', strtotime($book['date'])))."）") ?></div>

<div class="normal">课程：<?= Html::encode($book['cou_name']) ?></div>

<div class="normal">预约人：<?= Html::encode($book['nickname']."（{$book['phone']}）") ?></div>

<div class="highlight">备注：<?= Html::encode($book['remark']) ?></div>