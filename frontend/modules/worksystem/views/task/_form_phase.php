<?php

use common\models\worksystem\WorksystemTask;
use yii\helpers\Html;


/* @var $model WorksystemTask */

$statusProgress = '';
/** 先创建一条线状态 */
$statusArray = [
    WorksystemTask::STATUS_DEFAULT => '创建',
    WorksystemTask::STATUS_WAITCHECK => '审核',
    WorksystemTask::STATUS_ADJUSTMENTING => '审核',
    WorksystemTask::STATUS_CHECKING => '审核',
    WorksystemTask::STATUS_WAITASSIGN => '审核',
    WorksystemTask::STATUS_WAITUNDERTAKE => '审核',
    WorksystemTask::STATUS_TOSTART => '制作',
    WorksystemTask::STATUS_WORKING => '制作',
    WorksystemTask::STATUS_WAITACCEPTANCE => '验收',
    WorksystemTask::STATUS_UPDATEING => '验收',
    WorksystemTask::STATUS_ACCEPTANCEING => '验收',
    WorksystemTask::STATUS_COMPLETED => '完成',
];
//过滤，重组阶段
$phaseArray = [];
foreach ($statusArray as $status => $phase){
    $phaseArray[$phase] = $status;
}
/** 已取消单独显示 */
if($model->status == WorksystemTask::STATUS_CANCEL){
    $statusProgress = '<div class="phase have-to"><p class="phase-words">取消</p></div>';
}else{    
    foreach ($phaseArray as $index => $items){
        $isHidden = $index != $statusArray[$model->status] ? ' hidden-xs' : '';
        $haDone = $index == $statusArray[$model->status] || $items <= $model->status;
        $statusProgress .= '<div class="phase'.($haDone ? ' have-to' : ' not-to').$isHidden.'"><p class="phase-words">'.$index.'</p></div>';
        $statusProgress .= $index == $statusArray[WorksystemTask::STATUS_COMPLETED] ?  null : Html::img(['/filedata/worksystem/image/arrow.png'], ['class' => 'arrow hidden-xs']);
    }
}

echo $statusProgress;