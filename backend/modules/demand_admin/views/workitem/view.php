<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandWorkitemTemplate */

$this->title = !empty($model->workitem_id) ? $model->workitem->name : null;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Workitem Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-workitem-template-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/demand', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'demand_workitem_template_type_id',
                'value' => !empty($model->demand_workitem_template_type_id) ? $model->demandWorkitemTemplateType->name : null,
            ],
            [
                'attribute' => 'workitem_type_id',
                'value' => !empty($model->workitem_type_id) ? $model->workitemType->name : null,
            ],
            [
                'attribute' => 'workitem_id',
                'value' => !empty($model->workitem_id) ? $model->workitem->name : null,
            ],
            [
                'attribute' => 'is_new',
                'value' =>$model->is_new == true ? '是' : '否',
            ],
           'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

</div>
