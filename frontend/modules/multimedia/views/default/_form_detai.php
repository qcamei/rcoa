<?php

use common\models\multimedia\MultimediaTask;
use kartik\widgets\Select2;
use wskeee\rbac\RbacName;
use wskeee\utils\DateUtil;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this View */
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
?>

<?php 
    echo DetailView::widget([
        'model' => $model,
        //'options' => ['class' => 'table table-bordered detail-view'],
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            ['label' => '<span class="btn-block viewdetail-th-head">基本信息</span>','value' => ''],
            [
                'attribute' => 'item_type_id',
                'value' => !empty($model->item_type_id) ? $model->itemType->name: '空',
            ],
            [
                'attribute' => 'item_id',
                'value' => !empty($model->item_id) ? $model->item->name : '空',
            ],
            [
                'attribute' => 'item_child_id',
                'value' => !empty($model->item_child_id) ? $model->itemChild->name : '空',
            ],
            [
                'attribute' => 'course_id',
                'value' => !empty($model->course_id) ? $model->course->name : '空',
            ],
            [
                'attribute' => 'name',
                'value' => $model->name,
            ],
            [
                'attribute' => 'material_video_length',
                'value' => DateUtil::intToTime($model->material_video_length),
            ],
            [
                'attribute' => 'production_video_length',
                'value' => !empty($model->production_video_length) ? 
                            DateUtil::intToTime($model->production_video_length) : null,
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Workload'),
                'format' => 'raw',
                'value' =>  !empty($model->production_video_length) ? 
                            $workload[0].'<span class="proportion">(1分钟成品 = '.$workload[1].'个标准工作量)</span>' : null,
            ],
            [
                'attribute' => 'content_type',
                'format' => 'raw',
                'value' => !empty($model->content_type) ? 
                           '<span class="content-type">'.$model->contentType->name.'</span>' : null ,
            ],
            
            ['label' => '<span class="btn-block viewdetail-th-head">开发信息</span>','value' => ''],
            [
                'attribute' => 'plan_end_time',
                'format' => 'raw',
                'value' => '<span class="complete-time">'.$model->plan_end_time.'</span>',
            ],
            [
                'attribute' => 'level',
                'format' => 'raw',
                'value' => $model->level != MultimediaTask::LEVEL_URGENT ? MultimediaTask::$levelName[$model->level] : 
                           '<span class="level">'.MultimediaTask::$levelName[$model->level].'</span>',
            ],
            [
                'attribute' => 'make_team',
                'format' => 'raw',
                'value' => !empty($model->make_team) ? 
                           '<span class="team-span" style="float: left;">'.$model->createTeam->name.'</span>'.
                           Html::img(['/filedata/multimedia/image/brace.png'], [
                               'width' => '20', 'height' => '20', 'style' => 'float: left; margin: 0 3px;'
                           ]).'<span class="team-span" style="float: left;">'.$model->makeTeam->name.'</span>'
                           : (!empty($model->create_team) ? '<span class="team-span">'.$model->createTeam->name.'</span>' : ''),
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Producer'),
                'format' => 'raw',
                'value' =>  !empty($producer) ? implode(',', $producer) : null,
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Status').'/'.Yii::t('rcoa/multimedia', 'Progress'),
                'format' => 'raw',
                'value' => $statusProgress,
            ],
            [
                'attribute' => 'path',
                'value' => $model->path,
            ],
            
            ['label' => '<span class="btn-block viewdetail-th-head">其它信息</span>','value' => ''],
            [
                'attribute' => 'create_team',
                'value' => !empty($model->create_team) ? $model->createTeam->name : null,
            ],
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
                'attribute' => 'real_carry_out',
                'value' => !empty($model->real_carry_out) ? $model->real_carry_out : null,
            ],
            [
                'attribute' => 'des',
                'format' => 'raw',
                'value' => '<div style="height:65px; vertical-align:middle; display:table-cell">'.$model->des.'</div>',
            ],
        ]   
    ]); 
?>

<?php
//$producerList = json_encode($producerList);
$js = 
<<<JS
    var producerList = '';
    $('#producer-select').change(function()
    {
        //$('<option/>').val('').text('请选择...').appendTo($(this));
        $.each(producerList, function(i, e)
        {
            console.log(i);
            console.log(e);
            $('<option>').val(e.i).text(e.i).appendTo($(this));
        });
    });
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

