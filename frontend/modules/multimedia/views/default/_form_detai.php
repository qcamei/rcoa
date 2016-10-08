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
foreach (MultimediaTask::$statusNmae as $key => $value) {
    $isHidden = $key != $model->status ? ' hidden-xs' : '';
    $isHiddenCancel = $key == MultimediaTask::STATUS_CANCEL ? ' hidden-lg hidden-md hidden-sm' : '';
    $progress = $key != MultimediaTask::STATUS_CANCEL ? MultimediaTask::$statusProgress[$key] : $model->progress;
    if($model->status == MultimediaTask::STATUS_CANCEL){
        $statusProgress =  '<div class="status-progress-div have-to">'
                            .'<p class="have-to-status">'.$value.'</p><p class="progress-strip">('
                            .$progress.'%)</p></div>';
    }else if($key <= $model->status) {
        $statusProgress .=  '<div class="status-progress-div have-to'.$isHidden.$isHiddenCancel.'">'
                            .'<p class="have-to-status">'.$value.'</p><p class="progress-strip">('
                            .$progress.'%)</p></div>';
    }else{
        $statusProgress .=  '<div class="status-progress-div not-to'.$isHidden.$isHiddenCancel.'">'
                            .'<p class="not-to-status">'.$value.'</p></div>';
    }
    if($key == MultimediaTask::STATUS_COMPLETED || $key == MultimediaTask::STATUS_CANCEL)
        $statusProgress .= '';
    else
        $statusProgress .= '<img src="/filedata/multimedia/image/direction-arrow.png" class="direction-arrow hidden-xs" />';
        
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
                'value' => DateUtil::intToTime($model->production_video_length),
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Workload'),
                'format' => 'raw',
                'value' => DateUtil::intToTime($workload[0]).'<span class="proportion">(1:'.$workload[1].')</span>',
            ],
            [
                'attribute' => 'content_type',
                'value' => !empty($model->content_type) ? $model->contentType->name : '空',
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
                'value' => $model->make_team != $model->create_team ? 
                           '<span class="team-span" style="float: left;">'.$model->createTeam->name.'</span>'.
                           Html::img(['/filedata/multimedia/image/brace.png'], [
                               'width' => '20', 'height' => '20', 'style' => 'float: left; margin: 0 3px;'
                           ]).'<span class="team-span" style="float: left;">'.$model->makeTeam->name.'</span>'
                           :'<span class="team-span">'.$model->makeTeam->name.'</span>',
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Producer'),
                'format' => 'raw',
                'value' =>  !empty($producer) ? implode(',', $producer) : '空',
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Status').Yii::t('rcoa/multimedia', 'Progress'),
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
                'value' => $model->createTeam->name,
            ],
            [
                'attribute' => 'create_by',
                'value' => $model->createBy->nickname,
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
                'value' => $model->real_carry_out,
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

