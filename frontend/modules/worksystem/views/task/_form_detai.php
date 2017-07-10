<?php

use common\models\worksystem\WorksystemTask;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model WorksystemTask */

$statusProgress = $this->render('_form_phase', [
    'model' => $model,
]);

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
                'format' => 'raw',
                'value' => !empty($model->item_type_id) ? $model->itemType->name: '空',
            ],
            [
                'attribute' => 'item_id',
                'format' => 'raw',
                'value' => !empty($model->item_id) ? $model->item->name : '空',
            ],
            [
                'attribute' => 'item_child_id',
                'format' => 'raw',
                'value' => !empty($model->item_child_id) ? $model->itemChild->name : '空',
            ],
            [
                'attribute' => 'course_id',
                'format' => 'raw',
                'value' => !empty($model->course_id) ? $model->course->name : '空',
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => $model->name,
            ],
            ['label' => '<span class="btn-block viewdetail-th-head">开发信息</span>','value' => ''],
            [
                'attribute' => 'level',
                'format' => 'raw',
                'value' => $model->level == WorksystemTask::LEVEL_ORDINARY ? '普通' : 
                           '<span class="error-warn">加急</span>',
            ],
            [
                'attribute' => 'is_epiboly',
                'format' => 'raw',
                'value' => $model->is_epiboly == false ? '否' : '是',
            ],
            [
                'attribute' => 'task_type_id',
                'format' => 'raw',
                'value' => !empty($model->task_type_id) ? $model->worksystemTaskType->name : null,
            ],
            [
                'label' => Yii::t('rcoa/worksystem', 'Task Cost'),
                'format' => 'raw',
                'value' =>  !empty($model->budget_cost) || !empty($model->reality_cost) ? 
                            '￥'.number_format($model->budget_cost, 2, '.', ',').' / ￥'.number_format($model->reality_cost, 2, '.', ',') : null,
            ],
            [
                'label' => Yii::t('rcoa/worksystem', 'Task Bonus'),
                'format' => 'raw',
                'value' => !empty($model->budget_bonus) || !empty($model->reality_bonus) ? 
                            '￥'.number_format($model->budget_bonus, 2, '.', ',').' / ￥'.number_format($model->reality_bonus, 2, '.', ',') : null,
            ],
            [
                'attribute' => 'plan_end_time',
                'format' => 'raw',
                'value' => '<span class="error-warn">'.$model->plan_end_time.'</span>',
            ],
            [
                'attribute' => 'external_team',
                'format' => 'raw',
                'value' => !empty($model->external_team) && !empty($model->create_team) ? 
                           ($model->external_team != $model->create_team && $model->getIsSeekEpiboly() ? '<span class="team-span team-span-left">'.$model->createTeam->name.'</span>'. Html::img(['/filedata/worksystem/image/brace.png'], ['class' => 'brace']) . '<span class="team-span team-span-left epiboly-team-span">'.$model->externalTeam->name.'</span>' : 
                           ($model->external_team != $model->create_team && $model->getIsSeekBrace() ? '<span class="team-span team-span-left">'.$model->createTeam->name.'</span>'. Html::img(['/filedata/worksystem/image/brace.png'], ['class' => 'brace']) . '<span class="team-span team-span-left">'.$model->externalTeam->name.'</span>' : '<span class="team-span">'.$model->createTeam->name.'</span>')) : null,
            ],
            [
                'label' => Yii::t('rcoa/worksystem', 'Producer'),
                'format' => 'raw',
                'value' =>  !empty($producer) ? $producer : null,
            ],
            [
                'label' => Yii::t('rcoa/worksystem', 'Phase'),
                'format' => 'raw',
                'value' => null //$statusProgress,
            ],
            ['label' => '<span class="btn-block viewdetail-th-head">其它信息</span>','value' => ''],
            [
                'attribute' => 'create_team',
                'format' => 'raw',
                'value' => !empty($model->create_team) ? '<span class="team-span">'.$model->createTeam->name.'</span>' : null,
            ],
            [
                'attribute' => 'create_by',
                'format' => 'raw',
                'value' => !empty($model->create_by) ? $model->createBy->nickname : null,
            ],
            [
                'attribute' => 'created_at',
                'format' => 'raw',
                'value' => date('Y-m-d H:i', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('Y-m-d H:i', $model->updated_at),
            ],
            [
                'attribute' => 'finished_at',
                'format' => 'raw',
                'value' => !empty($model->finished_at) ? date('Y-m-d H:i', $model->finished_at) : null,
            ],
            [
                'attribute' => 'des',
                'format' => 'raw',
                'value' => '<div style="height:65px; vertical-align:middle; display:table-cell">'.$model->des.'</div>',
            ],
        ]   
    ]); 
?>

