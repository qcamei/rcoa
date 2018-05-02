<?php

use common\models\demand\DemandWeightTemplate;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model DemandWeightTemplate */

$this->title = !empty($model->workitem_type_id) ? $model->workitemType->name : null;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Weight Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-weight-template-view">

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
                'attribute' => 'workitem_type_id',
                'value' => !empty($model->workitem_type_id) ? $model->workitemType->name : null,
            ],
            'weight',
            'sl_weight',
            'zl_weight',
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

</div>
