<?php

use common\models\teamwork\CourseManage;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model CourseManage */

/*$producers = [];
foreach ($producer as $value) {
    $producers[] = $value['is_leader'] == 'Y'?
    '<span style="margin:5px;color:red;">'.$value['nickname'].'(队长)</span>':
    '<span style="margin:5px;">'.$value['nickname'].'</span>';
}*/

?>
<div class="item-manage-view">
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
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
                'attribute' => 'lession_time',
                'value' => $model->lession_time,
            ],
            [
                'attribute' => 'team_id',
                'value' => $model->team->name,
            ],
            [
                'attribute' => 'create_by',
                'value' => $model->project->teamMember->team->name.' ( '.$model->createBy->nickname.' )',
            ],
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d H:i', $model->created_at),
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
                'attribute' => '当前进度',
                'value' => (int)($model->progress * 100).'%',
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->project->statusName[$model->status],
            ],
            [
                'attribute' => 'des',
                'format' => 'raw',
                'value' => '<div style="height:65px;">'.$model->des.'</div>',
            ],
            [
                'attribute' => '资源制作人',
                'format' => 'raw',
                'value' => empty($producer)? '无' :implode('', $producer),
            ],
        ],
    ]) ?>

</div>
