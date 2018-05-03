<?php

use common\models\need\NeedTask;

/* @var $model NeedTask */

$phase = '';

$statusArray = [
    NeedTask::STATUS_CREATEING => '发布',
    NeedTask::STATUS_AUDITING => '审核',
    NeedTask::STATUS_CHANGEAUDIT => '审核',
    NeedTask::STATUS_WAITRECEIVE => '开发',
    NeedTask::STATUS_WAITSTART => '开发',
    NeedTask::STATUS_DEVELOPING => '开发',
    NeedTask::STATUS_CHECKING => '验收',
    NeedTask::STATUS_CHANGECHECK => '验收',
    NeedTask::STATUS_FINISHED => '验收',
];

$percentArray = [
    '发布' => '10%',
    '审核' => '20%',
    '开发' => '80%',
    '验收' => '100%',
];

$phaseArray = [];
foreach ($statusArray as $status => $name) {
    $phaseArray[$name] = $status;
}

foreach ($phaseArray as $name => $number){
    $is_present =  $number <= $model->status;
    $is_hidden = $name != $statusArray[$model->status] ? ' hidden-xs' : '';
    $phase .= '<div class="phase ' . ($is_present ? 'has-to' : 'not-to') . $is_hidden . '"><span class="words">' . $name . '</span><span class="percent">(' . $percentArray[$name] . ')</span></div>';
    $phase .= $name == $statusArray[NeedTask::STATUS_FINISHED]  ?  null : '<div class="arrow hidden-xs"><i class="glyphicon glyphicon-arrow-right"></i></div>';
    
}

echo $phase;