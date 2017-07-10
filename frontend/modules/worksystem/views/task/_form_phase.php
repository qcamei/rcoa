<?php

use common\models\multimedia\MultimediaTask;

/* @var $model MultimediaTask */

$statusProgress = '';
/** 先创建一条线状态 */
$status = [
    MultimediaTask::STATUS_ASSIGN,
    MultimediaTask::STATUS_TOSTART,
    MultimediaTask::STATUS_WORKING,
];
/** 待审核、审核中、修改中 只保留一个 */
if($model->status == MultimediaTask::STATUS_UPDATEING || $model->status == MultimediaTask::STATUS_CHECKING)
    $status [] = $model->status;
else
    $status [] = MultimediaTask::STATUS_WAITCHECK;
//强制添加 完成状态
$status [] = MultimediaTask::STATUS_COMPLETED;

//已取消或者已完成状态单独显示
if($model->status == MultimediaTask::STATUS_CANCEL || $model->status == MultimediaTask::STATUS_COMPLETED)
{
    $statusProgress =  '<div class="status-progress-div have-to">'
                            .'<p class="have-to-status">'.MultimediaTask::$statusNmae[$model->status].'</p>'
                            .'<p class="progress-strip">('.$model->progress.'%)</p>'
                        . '</div>';
}else{
    foreach ($status as $status_value){
        //小屏时显示一个状态
        $isHidden = $status_value != $model->status ? ' hidden-xs' : '';
         /** 如果$status_value <= 当前状态输出样式"have-to"和显示进度 否则输出"not-to"和不显示进度 */
        $haDone = $status_value <= $model->status;
        $statusProgress .=  '<div class="status-progress-div '.($haDone ? 'have-to' : 'not-to').$isHidden.'">'
                                .'<p class="have-to-status">'.MultimediaTask::$statusNmae[$status_value].'</p>'
                     .($haDone ? '<p class="progress-strip">('.MultimediaTask::$statusProgress[$status_value].'%)</p>' : '') .
                            '</div>';
        $statusProgress .= $status_value == MultimediaTask::STATUS_COMPLETED ? '' : '<img src="/filedata/multimedia/image/direction-arrow.png" class="direction-arrow hidden-xs" />';
    }
}

echo $statusProgress;