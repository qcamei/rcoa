<?php

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

?>
<div class="course-manage-view">
    
    <?php
    echo DetailView::widget([
        'model' => $model,
        //'options' => ['class' => 'table table-bordered detail-view'],
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">基本信息</span>','value' => ''],
            [
                'attribute' => 'project.item_type_id',
                'value' => !empty($model->project->item_type_id) ? $model->project->itemType->name : null,
            ],
            [
                'attribute' => 'project.item_id',
                'value' => !empty($model->project->item_id) ? $model->project->item->name : null,
            ],
            [
                'attribute' => 'project.item_child_id',
                'value' => !empty($model->project->item_child_id) ? $model->project->itemChild->name : null,
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
                'attribute' => 'credit',
                'value' => $model->credit,
            ],
            [
                'attribute' => 'lession_time',
                'value' => $model->lession_time,
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
            ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">开发信息</span>','value' => ''],
            [
                'attribute' => 'mode',
                'format' => 'raw',
                'value' => CourseManage::$modeName[$model->mode],
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
                'value' => !empty($model->course_ops) ? $model->courseOps->user->nickname : null,
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
                'attribute' => Yii::t('rcoa/teamwork', 'Now Progress'),
                'value' => (int)($model->progress * 100).'%',
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->status == CourseManage::STATUS_WAIT_START ? 
                        '<span style="color: #F00">'.$model->getStatusName().'</span>' : $model->getStatusName(),
            ],
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => !empty($model->path) ? $model->path : null,
            ],
            ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">其它信息</span>','value' => ''],
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
