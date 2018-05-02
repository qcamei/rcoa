<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\shoot\ShootBookdetail;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
       您好！您已经被指派为【{课程名称}】拍摄预约任务的摄影师。 
    预约时间 ：【{场地}】{时间} 
    接洽人   ：{接洽人}｛手机｝ 
    备注     ：{备注} 
 
     马上查看(连接到任务详细页) 
 */
?>
<div class="mail-new-shoot">
    
    <p><b><?= Html::encode($model->shootMan->nickname) ?></b> 您好！您已经被指派为<b>【<?= Html::encode($model->fwCourse->name) ?>】</b>拍摄预约任务的摄影师。</p>

    <p><b>预约时间</b>：【<?= Html::encode($model->site->name) ?>】 <?= Html::encode($bookTime) ?> <?= Html::encode($model->start_time) ?></p>
    
    <p><b>接洽人</b>： <b><?= Html::encode($model->contacter->nickname) ?></b>(<?= Html::encode($model->contacter->phone) ?>)</p>
    
    <p><b>备注</b>：<?= Html::encode($model->remark) ?></p>
    
    <?= Html::a('马上查看', 
            Yii::$app->urlManager->createAbsoluteUrl(['/shoot/bookdetail/view','id'=>$b_id]), 
            [   
                'class'=>'btn btn-default', 
                'target'=>'_blank'
            ]) ?>
</div>
