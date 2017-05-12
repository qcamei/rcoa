<?php

use common\models\demand\DemandTask;
use wskeee\utils\DateUtil;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model DemandTask */

/*$statusProgress = $this->render('_status_progress', [
    'model' => $model,
]);*/

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

/*$workitem = $this->render('_workitem', [
    'workitmType' => $workitmType,
    'workitems' => $workitem,
]);*/

$workitem = '';
$array_first = reset($workitmType);  //获取数组的第一个元素
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
?>


<div class="demand-task-view ">
<?= DetailView::widget([
        'model' => $model,
        //'options' => ['class' => 'table table-bordered detail-view'],
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">基本信息</span>','value' => ''],
            [
                'attribute' => 'item_type_id',
                'value' => !empty($model->item_type_id) ? $model->itemType->name : null,
            ],
            [
                'attribute' => 'item_id',
                'value' => !empty($model->item_id) ? $model->item->name : null,
            ],
            [
                'attribute' => 'item_child_id',
                'value' => !empty($model->item_child_id) ? $model->itemChild->name : null,
            ],
            [
                'attribute' => 'course_id',
                'value' => !empty($model->course_id) ? $model->course->name : null,
            ],
            [
                'attribute' => 'teacher',
                'value' => !empty($model->teacher) ? $model->speakerTeacher->nickname : null,
            ],
            [
                'attribute' => 'lesson_time',
                'value' => $model->lesson_time,
            ],
            [
                'attribute' => 'credit',
                'value' => $model->credit,
            ],
            [
                'attribute' => 'course_description',
                'format' => 'raw',
                'value' => '<div style="height:65px; vertical-align:middle; display:table-cell">'.$model->course_description.'</div>',
            ],
            ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">开发信息</span>','value' => ''],
            [
                'attribute' => 'mode',
                'format' => 'raw',
                'value' => $model->mode == DemandTask::MODE_NEWBUILT ?
                    Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).DemandTask::$modeName[$model->mode] : 
                    Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).DemandTask::$modeName[$model->mode] ,
            ],
            [
                'attribute' => 'team_id',
                'format' => 'raw',
                'value' => !empty($model->team_id) ? '<span class="team-span">'.$model->team->name.'</span>' : null,
            ],
            [
                'attribute' => 'undertake_person',
                'format' => 'raw',
                'value' => !empty($model->undertake_person) ? $model->undertakePerson->nickname : null,
            ],
            [
                'attribute' => 'develop_principals',
                'format' => 'raw',
                'value' => !empty($model->develop_principals) ? $model->developPrincipals->user->nickname : null,
            ],
            [
                'attribute' => 'plan_check_harvest_time',
                'value' => $model->plan_check_harvest_time,
            ],
            [
                'attribute' => 'reality_check_harvest_time',
                'value' => $model->reality_check_harvest_time,
            ],
            [
                'attribute' => Yii::t('rcoa/multimedia', 'Status').'/'.Yii::t('rcoa/multimedia', 'Progress'),
                'format' => 'raw',
                'value' => !empty($statusProgress) ? $statusProgress : null,
            ],
            [
                'label' => Yii::t('rcoa/workitem', 'Workitems'),
                'format' => 'raw',
                'value' => !empty($workitem) ? $workitem : null,
            ],
            [
                'attribute' => 'bonus_proportion',
                'value' => !empty($model->bonus_proportion) ? number_format($model->bonus_proportion * 100).'%' : null, 
            ],
            [
                'label' => Yii::t('rcoa/demand', 'Budget Cost'),
                'format' => 'raw',
                'value' => (!empty($model->budget_cost) ? '￥'. number_format($model->budget_cost + $model->budget_cost * $model->bonus_proportion, 2).'<span class="pattern">（预算成本 = 预算开发成本 + 预算开发成本 × 绩效分值）</span>' : null ), 
            ],
            [
                'label' => Yii::t('rcoa/demand', 'Total Cost'),
                'format' => 'raw',
                'value' => (!empty($model->cost) ? '￥'. number_format($model->cost + $model->cost * $model->bonus_proportion, 2).'<span class="pattern">（实际成本 = 实际开发成本 + 实际开发成本 × 绩效分值）</span>' : null ), 
            ],
            ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">其它信息</span>','value' => ''],
            [
                'attribute' => 'create_by',
                'value' => !empty($model->create_by) ? $model->createBy->nickname : null,
            ],
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d H:i', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('Y-m-d H:i', $model->updated_at),
            ],
            [
                'attribute' => 'finished_at',
                'value' => !empty($model->finished_at) ? date('Y-m-d H:i', $model->finished_at) : null,
            ],
            [
                'attribute' => 'des',
                'format' => 'raw',
                'value' => '<div style="height:65px; vertical-align:middle; display:table-cell">'.$model->des.'</div>',
            ],
        ],
    ]) 
?>
</div>
