<?php

use common\models\multimedia\MultimediaTask;
use yii\helpers\Html;

/**
 *  您好！制作任务已取消，请知晓!
    课程名 ：{课程名称}
    任务名称 ：{任务名称} {审核标题}
    备注 ：{取消原因}
    马上查看(连接到任务详细页)


 */

 /* @var $model MultimediaTask */

?>

<div class="mail-new-multimedia">
    
    <p>您好！制作任务已取消，请知晓！</p>

    <p><b>课程名</b>：<?= Html::encode($model->course->name) ?></p>
    
    <p><b>任务名称</b>：<?= Html::encode($model->name) ?></p>
    
    <p><b>备注</b>：<?= Html::encode($cancel) ?></p>
    
    <?= Html::a('马上查看', 
            Yii::$app->urlManager->createAbsoluteUrl(['/multimedia/default/view','id' => $model->id]), 
            [   
                'class'=>'btn btn-default', 
                'target'=>'_blank'
            ]) ?>
</div>

