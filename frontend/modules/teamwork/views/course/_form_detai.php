<?php

use common\models\demand\DemandTask;
use common\models\team\TeamMember;
use common\models\teamwork\CourseManage;
use wskeee\utils\DateUtil;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model CourseManage */

$producer = [];
foreach ($producers as $element) {
    $key = ArrayHelper::getValue($element, 'producer');
    $value = ArrayHelper::getValue($element, 'producerOne.is_leader') == TeamMember::TEAMLEADER ? 
            '<span class="team-leader developer">'.
                ArrayHelper::getValue($element, 'producerOne.user.nickname').
             '</span>' : 
            '<span class="developer">'.
                ArrayHelper::getValue($element, 'producerOne.user.nickname'). 
           '</span>';
    $producer[$key] = $value;
}

CourseManage::$progress = ArrayHelper::map($twTool->getCourseProgress($model->id)->all(), 'id', 'progress');
?>

<div class="course-manage-view">
    
    <?php
    echo DetailView::widget([
        'model' => $model,
        //'options' => ['class' => 'table table-bordered detail-view'],
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            ['label' => '<span class="btn-block viewdetail-th-head">基本信息</span>','value' => ''],
            [
                'attribute' => 'demandTask.item_type_id',
                'value' => !empty($model->demandTask->item_type_id) ? $model->demandTask->itemType->name : null,
            ],
            [
                'attribute' => 'demandTask.item_id',
                'value' => !empty($model->demandTask->item_id) ? $model->demandTask->item->name : null,
            ],
            [
                'attribute' => 'demandTask.item_child_id',
                'value' => !empty($model->demandTask->item_child_id) ? $model->demandTask->itemChild->name : null,
            ],
            [
                'attribute' => 'demandTask.course_id',
                'value' => !empty($model->demandTask->course_id) ? $model->demandTask->course->name : null,
            ],
            [
                'attribute' => 'demandTask.teacher',
                'value' => !empty($model->demandTask->teacher) ? $model->demandTask->speakerTeacher->nickname : null,
            ],
            [
                'attribute' => 'demandTask.lesson_time',
                'value' => $model->demandTask->lesson_time,
            ],
            [
                'attribute' => 'demandTask.credit',
                'value' => $model->demandTask->credit,
            ],
            [
                'attribute' => 'video_length',
                'value' => DateUtil::intToTime($model->video_length),
            ],
            [
                'attribute' => 'question_mete',
                'value' => $model->question_mete,
            ],
            [
                'attribute' => 'case_number',
                'value' => $model->case_number,
            ],
            [
                'attribute' => 'activity_number',
                'value' => $model->activity_number,
            ],
            ['label' => '<span class="btn-block viewdetail-th-head">开发信息</span>','value' => ''],
            [
                'attribute' => 'demandTask.mode',
                'format' => 'raw',
                'value' => $model->demandTask->mode == DemandTask::MODE_NEWBUILT ?
                    Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).DemandTask::$modeName[$model->demandTask->mode] : 
                    Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).DemandTask::$modeName[$model->demandTask->mode],
            ],
            [
                'attribute' => 'team_id',
                'format' => 'raw',
                'value' => !empty($model->team_id) ? '<span class="team-span">'.$model->team->name.'</span>' : null,
            ],
            [
                'attribute' => Yii::t('rcoa/teamwork', 'Resource People'),
                'format' => 'raw',
                'value' => !empty($producer)?  implode('', $producer) : null,
            ],
            [
                'attribute' => 'course_ops',
                'value' => !empty($model->course_ops) ? $model->courseOps->nickname : null,
            ],
            [
                'attribute' => 'plan_start_time',
                'value' => $model->plan_start_time,
            ],
            [
                'attribute' => 'plan_end_time',
                'value' => $model->plan_end_time,
            ],
            [
                'attribute' => 'real_start_time',
                'value' => $model->real_start_time,
            ],
            [
                'attribute' => 'real_carry_out',
                'value' => $model->real_carry_out,
            ],
            [
                'attribute' => Yii::t('rcoa/multimedia', 'Status').'/'.Yii::t('rcoa/multimedia', 'Progress'),
                'format' => 'raw',
                'value' => '<div class="status-progress-div '.($model->status != CourseManage::STATUS_WAIT_START ? 'status-have-to' : 'status-not-to').'">'.
                                '<p class="have-to-status">'. CourseManage::$statusName[$model->status].'</p>'.
                                ($model->status != CourseManage::STATUS_WAIT_START ? '<p class="progress-strip">('.CourseManage::$progress[$model->id].'%)</p>' : '').
                           '</div>',
            ],
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => !empty($model->path) ? $model->path : null,
            ],
            ['label' => '<span class="btn-block viewdetail-th-head">其它信息</span>','value' => ''],
            [
                'attribute' => 'create_by',
                'value' => !empty($model->create_by) ? $model->createBy->nickname : null,
            ],
            [
                'attribute' => 'course_principal',
                'format' => 'raw',
                'value' => !empty($model->course_principal) ?  $model->coursePrincipal->user->nickname :
                        (!empty($model->create_by) ? $model->createBy->nickname : null),
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
                'value' => '<div style="height:65px; vertical-align:middle; display:table-cell">'.$model->des.'</div>',
            ],
        ],
    ]) ?>

</div>
