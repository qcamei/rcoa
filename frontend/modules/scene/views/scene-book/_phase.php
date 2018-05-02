<?php

use common\models\scene\SceneBook;
use common\models\worksystem\WorksystemTask;
use yii\helpers\Html;


/* @var $model SceneBook */

$phases = null;
/** 先创建一条线状态 */
$statusArray = [
    SceneBook::STATUS_BOOKING => '创建',
    SceneBook::STATUS_ASSIGN => '指派',
    SceneBook::STATUS_SHOOTING => '拍摄',
    SceneBook::STATUS_APPRAISE => '评价',
    SceneBook::STATUS_COMPLETED => '完成',
];
//过滤，重组阶段
$phaseArray = [];
foreach ($statusArray as $status => $phase){
    $phaseArray[$phase] = $status;
}

/** 已失约 or 已取消 单独显示 */
if($model->status == SceneBook::STATUS_CANCEL){
    $phases = '<div class="phase have-to danger"><p class="phase-words">取消</p></div>';
}else if($model->status == SceneBook::STATUS_BREAK_PROMISE){
    $phases = '<div class="phase have-to danger"><p class="phase-words">失约</p></div>';
}else{    
    foreach ($phaseArray as $index => $items){
        $isHidden = $index != $statusArray[$model->status] ? ' hidden-xs' : '';
        $haDone = $index == $statusArray[$model->status] || $items <= $model->status;
        $phases .= '<div class="phase'.($haDone ? ' have-to' : ' not-to').$isHidden.'"><p class="phase-words">'.$index.'</p></div>';
        $phases .= $index == $statusArray[SceneBook::STATUS_COMPLETED] ?  null : Html::img(['/filedata/scene/icons/arrow.png'], ['class' => 'arrow hidden-xs']);
    }
}

echo $phases;