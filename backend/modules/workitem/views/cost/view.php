<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\workitem\WorkitemCost */

$this->title = !empty($model->workitem_id) ? $model->workitem->name : null;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/workitem', 'Workitem Costs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workitem-cost-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/workitem', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'workitem_id',
                'value' => !empty($model->workitem_id) ? $model->workitem->name : null,
            ],
            [
                'attribute' => 'cost_new',
                'value' => !empty($model->cost_new) ? '￥'.$model->cost_new : null,
            ],
            [
                'attribute' => 'cost_remould',
                'value' => !empty($model->cost_remould) ? '￥'.$model->cost_remould: null,
            ],
            'target_month',
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

</div>
