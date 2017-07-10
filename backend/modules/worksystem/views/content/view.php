<?php

use common\models\worksystem\WorksystemContent;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model WorksystemContent */

$this->title = $model->type_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-content-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/worksystem', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'worksystem_task_type_id',
                'value' => !empty($model->worksystem_task_type_id) ? $model->worksystemTaskType->name : null,
            ],
            'type_name',
            'icon',
            [
                'attribute' => 'price_new',
                'value' => '￥'. number_format($model->price_new, 2, '.', ','),
            ],
            [
                'attribute' => 'price_remould',
                'value' => '￥'. number_format($model->price_remould, 2, '.', ','),
            ],
            'unit',
            'des:ntext',
            'index',
            [
                'attribute' => 'is_delete',
                'value' => $model->is_delete == 0 ? '否' : '是',
            ],
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

</div>
