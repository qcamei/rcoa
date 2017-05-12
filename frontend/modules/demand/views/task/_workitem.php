<?php

use common\models\demand\DemandTask;

/* @var $model DemandTask */

$workitem = '';

$array_first = reset($workitmType);     //获取数组的第一个元素
foreach ($workitmType as $type) {
    if($array_first['id'] == $type['id'])
        $workitem .= '<p class="workitem-type">'.$type['name'].'<span class="mode">（新建, 改造）</span></p>';
    else
        $workitem .= '<p class="workitem-type" style="margin-top:20px">'.$type['name'].'<span class="mode">（新建,  改造）</span></p>';
    foreach ($workitems as $work) {
        if($work['workitem_type'] == $type['id']){
            $workitem .= '<p class="workitem"><span>'.$work['name'].'</span><span>（';
            foreach ($work['childs'] as $child) {
                if($child['is_new'])
                    $workitem .= $child['value'].$child['unit'].',  ';
                else
                    $workitem .= $child['value'].$child['unit'];
            }
            $workitem .= '）</span></p>';
        }
    }
}