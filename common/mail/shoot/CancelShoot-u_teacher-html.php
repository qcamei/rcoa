<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\shoot\ShootBookdetail;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
     
 */
?>
<div class="mail-new-shoot">
    <p><b><?= Html::encode($model->teacher->nickname)?></b>老师：</p>
    
    <p>您好！原计划<b><?= Html::encode($bookTime) ?> <?= Html::encode($model->start_time) ?> 【<?= Html::encode($model->fwCourse->name)?>】</b>的拍摄任务已经取消。</p>

    <p><b>原因</b>：<?= Html::encode($model->history->history) ?></p>
    
    <p>如果有问题，请与我联系 <b><?= Html::encode($model->booker->nickname) ?>(<?= Html::encode($model->booker->phone) ?>)</b> 谢谢！</p>
    
</div>