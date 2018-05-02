<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\shoot\ShootBookdetail;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
        您好！{课程名称}拍摄预约任务已经取消。 
    预约时间 ：【 {场地}】{时间} 
    原因     ：{任务取消的原因} 
 
     马上查看(连接到任务详细页) 
 */
?>
<div class="mail-new-shoot">
    
    <p>您好！<b>【<?= Html::encode($model->fwCourse->name) ?>】</b>拍摄预约任务已经取消。</p>

    <p><b>预约时间</b>：【<?= Html::encode($model->site->name) ?>】 <?= Html::encode($bookTime) ?> <?= Html::encode($model->start_time) ?></p>
    
    <p><b>原因</b>：<?= Html::encode($model->history->history) ?></p>
    
    <?= Html::a('马上查看', 
            Yii::$app->urlManager->createAbsoluteUrl(['/shoot/bookdetail/view','id'=>$b_id]), 
            [   
                'class'=>'btn btn-default', 
                'target'=>'_blank'
            ]) ?>
</div>