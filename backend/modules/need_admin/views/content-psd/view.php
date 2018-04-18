<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\need\NeedContentPsd */

$this->title = $model->workitem->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Content}{Template}',[
    'Content' => Yii::t('app', 'Content'),
    'Template' => Yii::t('app', 'Template'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="need-content-psd-view">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            'id',
            [
                'attribute' => 'workitem_type_id',
                'value' => $model->workitemType->name
            ],
            [
                'attribute' => 'workitem_id',
                'value' => $model->workitem->name
            ],
            [
                'attribute' => 'price_new',
                'value' => '￥' . $model->price_new
            ],
            [
                'attribute' => 'price_remould',
                'value' => '￥' . $model->price_remould
            ],
            'sort_order',
            'is_del',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
