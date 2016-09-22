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

?>
<?php $form = ActiveForm::begin(['id' => 'form-assign', 'action'=>'assign?id='.$model->id]); ?>

<?php 
    echo DetailView::widget([
        'model' => $model,
        //'options' => ['class' => 'table table-bordered detail-view'],
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">基本信息</span>','value' => ''],
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
                'value' => DateUtil::intToTime($workload[0]).'<span class="proportion">(1:'.$workload[1]['proportion'].')</span>',
            ],
            [
                'attribute' => 'content_type',
                'value' => !empty($model->content_type) ? $model->contentType->name : '空',
            ],
            
            ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">开发信息</span>','value' => ''],
            [
                'attribute' => 'carry_out_time',
                'format' => 'raw',
                'value' => '<span class="complete-time">'.$model->carry_out_time.'</span>',
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
                'value' =>  Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_ASSIGN) && $model->getIsStatusAssign()
                            && $multimedia->getIsAssignPerson($model->make_team) && $model->brace_mark == MultimediaTask::CANCEL_BRACE_MARK ? 
                            Select2::widget([
                                'id' => 'producer-select',
                                'name' => 'producer[]',
                                'value' => !empty($producer) ? array_keys($producer) : '',
                                'data' => $producerList,
                                'options' => [
                                    'placeholder' => '请选择制作人...',
                                    'multiple' => true
                                ],
                                'toggleAllSettings' => [
                                    'selectLabel' => '<i class="glyphicon glyphicon-ok-circle"></i> 添加全部',
                                    'unselectLabel' => '<i class="glyphicon glyphicon-remove-circle"></i> 取消全部',
                                    'selectOptions' => ['class' => 'text-success'],
                                    'unselectOptions' => ['class' => 'text-danger'],
                                ],
                                'pluginOptions' => [
                                    'tags' => false,
                                    'maximumInputLength' => 10,
                                    'allowClear' => true,
                                ],
                            ]) : (!empty($producer) ? implode(',', $producer) : '空'),
            ],
            [
                'attribute' => 'progress',
                'format' => 'raw',
                'value' => $model->progress.'%',
            ],
            [
                'attribute' => 'status',
                'value' => MultimediaTask::$statusNmae[$model->status],
            ],
            [
                'attribute' => 'path',
                'value' => $model->path,
            ],
            
            ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">其它信息</span>','value' => ''],
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
                'attribute' => 'des',
                'format' => 'raw',
                'value' => '<div style="height:65px;">'.$model->des.'</div>',
            ],
        ]   
    ]); 
?>

<?php ActiveForm::end(); ?>

<?php
//$producerList = json_encode($producerList);
$js = 
<<<JS
    var producerList = '';
    //console.log(producerList);
    $('#producer-select').change(function()
    {
        console.log(111);
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

