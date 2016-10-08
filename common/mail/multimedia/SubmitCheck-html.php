<?php

use common\models\multimedia\MultimediaTask;
use yii\helpers\Html;

/**
 *  您好！｛审核标题｝已经提交，请及时审核!
    等级 ：{等级}
    课程名 ：{课程名称}
    任务名称 ：{任务名称}
    需求时间 ：{需求时间}
    马上查看(连接到任务详细页)


 */

 /* @var $model MultimediaTask */

?>

<div class="mail-new-multimedia">
    
    <p>您好！<b><?= Html::encode($model->title)?></b>&nbsp;已经提交，请及时审核！</p>

    <p><b>等级</b>：
        <?php 
            if($model->task->level == MultimediaTask::LEVEL_URGENT)
                echo '<span style="color:red">'.Html::encode(MultimediaTask::$levelName[$model->task->level]).'</span>';
            else 
               echo Html::encode(MultimediaTask::$levelName[$model->task->level]);
        ?>
    </p>
    
    <p><b>课程名</b>：<?= Html::encode($model->task->course->name) ?></p>
    
    <p><b>任务名称</b>：<?= Html::encode($model->task->name) ?></p>
    
    <p><b>需求时间</b>：<span style="color:red"><?= Html::encode($model->task->plan_end_time) ?></span></p>
    
    <?= Html::a('马上查看', 
            Yii::$app->urlManager->createAbsoluteUrl(['/multimedia/default/view','id' => $model->task_id]), 
            [   
                'class'=>'btn btn-default', 
                'target'=>'_blank'
            ]) ?>
</div>

