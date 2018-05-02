<?php

use common\models\need\NeedTask;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model NeedTask */


$phase = $this->render('_phase', ['model' => $model]);

?>

<div class="col-xs-12 frame">
    
    <div class="col-xs-12 title">
        <i class="fa fa-file-text"></i>
        <span><?= Yii::t('app', '{Basic}{Info}',[
            'Basic' => Yii::t('app', 'Basic'),
            'Info' => Yii::t('app', 'Info'),
        ]) ?></span>
    </div>
    
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'business_id',
                'value' => !empty($model->business_id) ? $model->business->name : null,
            ],
            [
                'attribute' => 'layer_id',
                'value' => !empty($model->layer_id) ? $model->layer->name : null,
            ],
            [
                'attribute' => 'profession_id',
                'value' => !empty($model->profession_id) ? $model->profession->name : null,
            ],
            [
                'attribute' => 'course_id',
                'value' => !empty($model->course_id) ? $model->course->name : null,
            ],
            [
                'attribute' => 'task_name',
                'value' => $model->task_name,
            ],
            [
                'attribute' => 'level',
                'format' => 'raw',
                'value' => !$model->level ? '普通' : '<span class="danger">加急</span>',
            ],
            [
                'attribute' => 'performance_percent',
                'value' => $model->performance_percent * 100 . '%',
            ],
            [
                'attribute' => 'need_time',
                'format' => 'raw',
                'value' => '<span class="danger">' . date('Y-m-d H:i', $model->need_time) . '</span>',
            ],
            [
                'attribute' => 'finish_time',
                'value' => !empty($model->finish_time) ? date('Y-m-d H:i', $model->finish_time) : null,
            ],
            [
                'attribute' => 'audit_by',
                'value' => !empty($model->audit_by) ? $model->auditBy->nickname : null,
            ],
            [
                'attribute' => 'receive_by',
                'value' => !empty($model->receive_by) ? $model->receiveBy->nickname : null,
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatusName(),
            ],
            [
                'label' => '阶段/进度',
                'format' => 'raw',
                'value' => $phase,
            ],
            [
                'attribute' => 'save_path',
                'value' => !empty($model->save_path) ? $model->save_path : null,
            ],
            [
                'attribute' => 'created_by',
                'value' => !empty($model->created_by) ? $model->createdBy->nickname : null,
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
                'value' => '<div class="des">'.$model->des.'</div>',
            ],
        ],
    ]) ?>
    
</div>
