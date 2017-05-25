<?php

use common\models\demand\DemandTask;
use wskeee\utils\DateUtil;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model DemandTask */

$statusProgress = $this->render('_status_progress', [
    'model' => $model,
]);

$workitem = $this->render('_workitem', [
    'workitmType' => $workitmType,
    'workitems' => $workitems,
]);

?>


<div class="demand-task-view ">
<?= DetailView::widget([
        'model' => $model,
        //'options' => ['class' => 'table table-bordered detail-view'],
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            ['label' => '<span class="btn-block viewdetail-th-head">基本信息</span>','value' => ''],
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
            ['label' => '<span class="btn-block viewdetail-th-head">开发信息</span>','value' => ''],
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
                'format' => 'raw',
                'value' => '<span style="color:#ff0000">'.$model->plan_check_harvest_time.'</span>',
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
                'value' => (!empty($model->budget_cost) ? '￥'. number_format($model->budget_cost + $model->budget_cost * $model->bonus_proportion, 2).'<span class="pattern">（人工预算成本 = 人工预算成本 + 奖金）</span>' : null ), 
            ],
            [
                'attribute' => 'external_budget_cost',
                'value' => !empty($model->external_budget_cost) ? '￥'.number_format($model->external_budget_cost, 2) : null, 
            ],
            [
                'label' => Yii::t('rcoa/demand', 'Total Budget Cost'),
                'format' => 'raw',
                'value' => '￥'.number_format(($model->budget_cost + $model->budget_cost * $model->bonus_proportion) + $model->external_budget_cost, 2).'<span class="pattern">（总预算成本 = 人工预算成本 + 外部预算成本）</span>', 
            ],
            ['label' => '<span class="btn-block viewdetail-th-head">其它信息</span>','value' => ''],
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
