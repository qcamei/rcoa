<?php

use common\models\demand\DemandTask;

/* @var $model DemandTask */

$statusProgress = '';

/** 先创建一条线状态 */
$status = [];
/** 待审核、审核中、调整中 只保留一个 */
if($model->status < DemandTask::STATUS_DEVELOPING){
    if($model->status == DemandTask::STATUS_ADJUSTMENTING || $model->status == DemandTask::STATUS_CHECKING)
        $status [] = $model->status;
    else
        $status [] = DemandTask::STATUS_CHECK;
}else
    $status [] = DemandTask::STATUS_CHECKING;
//强制添加 承接状态
$status [] = DemandTask::STATUS_UNDERTAKE;
//强制添加 开发状态
$status [] = DemandTask::STATUS_DEVELOPING;
/** 待验收、修改中、验收中 只保留一个 */
if($model->status == DemandTask::STATUS_UPDATEING || $model->status == DemandTask::STATUS_ACCEPTANCEING)
    $status [] = $model->status;
else
    $status [] = DemandTask::STATUS_ACCEPTANCE;
/** 待确定和申诉中 只保留一个 */
if($model->status == DemandTask::STATUS_APPEALING)
    $status[] = $model->status;
else 
   $status [] = DemandTask::STATUS_WAITCONFIRM; 

//强制添加 完成状态
$status [] = DemandTask::STATUS_COMPLETED;

//已取消或者已完成状态单独显示
if($model->status == DemandTask::STATUS_CANCEL || $model->status == DemandTask::STATUS_COMPLETED)
{
    $statusProgress =  '<div class="status-progress-div have-to">'
                            .'<p class="have-to-status">'.DemandTask::$statusNmae[$model->status].'</p>'
                            .'<p class="progress-strip">('.$model->progress.'%)</p>'
                        . '</div>';
}else{
    foreach ($status as $status_value){
        //小屏时显示一个状态
        $isHidden = $status_value != $model->status ? ' hidden-xs' : '';
         /** 如果$status_value <= 当前状态输出样式"have-to"和显示进度 否则输出"not-to"和不显示进度 */
        $haDone = $status_value <= $model->status;
        $statusProgress .=  '<div class="status-progress-div '.($haDone ? 'have-to' : 'not-to').$isHidden.'">'
                                .'<p class="have-to-status">'.DemandTask::$statusNmae[$status_value].'</p>'
                     .($haDone ? '<p class="progress-strip">('.DemandTask::$statusProgress[$status_value].'%)</p>' : '') .
                            '</div>';
        $statusProgress .= $status_value == DemandTask::STATUS_COMPLETED ? '' : '<img src="/filedata/multimedia/image/direction-arrow.png" class="direction-arrow hidden-xs" />';
    }
}

echo $statusProgress;