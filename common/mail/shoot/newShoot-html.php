<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\shoot\ShootBookdetail;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
       您好！{预约人}{手机}已经申请了拍摄预约任务。 
    预约时间 ：【{场地}】{时间} 
    课程名   ：{课程名称} 
    备注     ：{备注} 
 
     马上查看(连接到任务详细页) 
 */
?>
<div class="mail-new-shoot">
    
    <p>您好！<b><?= Html::encode($model->booker->nickname) ?></b>(<?= Html::encode($model->booker->phone) ?>)已经申请了拍摄预约任务。</p>

    <p><b>预约时间</b>：【<?= Html::encode($model->site->name) ?>】 <?= Html::encode($bookTime) ?> <?= Html::encode($model->start_time) ?></p>
    
    <p><b>课程名</b>： <?= Html::encode($model->fwCourse->name) ?> </p>
    
    <p><b>备注</b>：<?= Html::encode($model->remark) ?></p>
    
    <?= Html::a('马上查看', 
            Yii::$app->urlManager->createAbsoluteUrl(['/shoot/bookdetail/view','id'=>$b_id]), 
            [   
                'class'=>'btn btn-default', 
                'target'=>'_blank'
            ]) ?>
</div>