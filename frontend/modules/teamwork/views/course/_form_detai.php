<?php

use common\models\team\Team;
use common\models\teamwork\CourseManage;
use kartik\widgets\Select2;
use wskeee\rbac\RbacName;
use wskeee\utils\DateUtil;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model CourseManage */

?>
<div class="course-manage-view">
    <?php $form = ActiveForm::begin(['id' => 'form-change', 'action'=>'change?id='.$model->id]); ?>
    
    <?php
    echo DetailView::widget([
        'model' => $model,
        //'options' => ['class' => 'table table-bordered detail-view'],
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">基本信息</span>','value' => ''],
            [
                'attribute' => 'project.item_type_id',
                'value' => $model->project->itemType->name,
            ],
            [
                'attribute' => 'project.item_id',
                'value' => $model->project->item->name,
            ],
            [
                'attribute' => 'project.item_child_id',
                'value' => $model->project->itemChild->name,
            ],
            [
                'attribute' => 'course_id',
                'value' => $model->course->name,
            ],
            [
                'attribute' => 'teacher',
                'value' => $model->speakerTeacher->nickname,
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
                'attribute' => 'team_id',
                'format' => 'raw',
                'value' => Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER) && $model->getIsNormal() ? 
                    Select2::widget([
                         'name' => 'CourseManage[team_id]',
                         'value' => $model->team_id,
                         'data' => $team,
                         'options' => [
                             'placeholder' => '选择团队...',
                         ],
                    ]) : $model->team->name,
            ],
            [
                'attribute' => Yii::t('rcoa/teamwork', 'Resource People'),
                'format' => 'raw',
                'value' => empty($producer)? '无' :implode('', $producer),
            ],
            [
                'attribute' => 'course_ops',
                'value' => empty($model->course_ops) ? '无'
                           :$model->courseOps->u->nickname.' ( '.$model->courseOps->position.' ) ',
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
                'value' => $model->project->statusName[$model->status],
            ],
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => empty($model->path) ? '空' : $model->path,
            ],
            ['label' => '<span class="btn-block viewdetail-th-head" style="width:100%">其它信息</span>','value' => ''],
            [
                'attribute' => 'create_by',
                'value' => $model->createBy->nickname,
            ],
            [
                'attribute' => 'course_principal',
                'format' => 'raw',
                'value' => Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER) && $model->getIsNormal() ? 
                    Select2::widget([
                         'name' => 'CourseManage[course_principal]',
                         'value' => empty($model->course_principal)?  $model->create_by : $model->course_principal,
                         'data' => $coursePrincipal,
                         'options' => [
                             'placeholder' => '选择课程负责人...',
                         ],
                    ]) : (empty($model->course_principal)?  $model->createBy->nickname : $model->coursePrincipal->u->nickname),
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
        ],
    ]) ?>

    <?php ActiveForm::end(); ?>
</div>
