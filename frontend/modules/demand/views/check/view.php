<?php

use common\models\demand\DemandCheck;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model DemandCheck */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-check-view">

    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title); ?></h4>
        </div>
        <div class="modal-body">
            
            <?= DetailView::widget([
                'model' => $model,
                'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
                'attributes' => [
                    [
                        'label' => '课程名称',
                        'value' => !empty($model->demand_task_id) ? $model->demandTask->course->name : null,
                    ],
                    [
                        'attribute' => 'title',
                        'value' => !empty($model->title) ? $model->title : null,
                    ],
                    [
                        'attribute' => 'content',
                        'format' => 'raw',
                        'value' => !empty($model->content) ? $model->content : '无',
                    ],
                    [
                        'attribute' => 'des',
                        'format' => 'raw',
                        'value' => !empty($model->des) ? $model->des : '无',
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
                ],
            ]) ?>
            
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" data-dismiss="modal" aria-label="Close">关闭</button>
        </div>
   </div>
    
</div>
