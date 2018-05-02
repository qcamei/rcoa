<?php

use common\models\demand\DemandCheckReply;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model DemandCheckReply */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Check Replies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-check-reply-view">

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
                        'value' => !empty($model->demand_check_id) ? $model->demandCheck->demandTask->course->name : null,
                    ],
                    [
                        'attribute' => 'title',
                        'value' => !empty($model->title) ? $model->title : null,
                    ],
                    [
                        'attribute' => 'pass',
                        'format' => 'raw',
                        'value' => $model->pass == true ? '<span class="btn btn-success btn-sm">通过</span>' : '<span class="btn btn-danger btn-sm">不通过</span>',
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
