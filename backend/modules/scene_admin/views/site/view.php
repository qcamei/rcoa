<?php

use common\models\scene\SceneSite;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model SceneSite */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Scene}{Administration}',[
    'Scene' => Yii::t('app', 'Scene'),
    'Administration' => Yii::t('app', 'Administration'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-site-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'op_type',
                'value' => ($model->country == 1) ? '自营' : '合作',
            ],
            'area',
            [
                'attribute' => 'country',
                'value' => ($model->country == 1) ? '中国' : $model->country,
            ],
            [
                'attribute' => 'province',
                'value' => $model->adds1->name,
            ],
            [
                'attribute' => 'city',
                'value' => $model->adds2->name,
            ],
            [
                'attribute' => 'district',
                'value' => $model->adds3->name,
            ],
            [
                'attribute' => 'twon',
                'value' => $model->adds4->name,
            ],
            'address',
            'price',
            [
                'attribute' => 'contact',
                'value' => $model->user->nickname,
            ],
            [
                'attribute' => 'manager_id',
                'value' => $model->maname->nickname,
            ],
            'content_type',
            [
                'attribute' => 'img_path',
                'format' => 'raw',
                'value' => Html::img(WEB_ROOT.$model->img_path),
            ],
            [
                'attribute' => 'is_publish',
                'value' => $model->is_publish == 0 ? Yii::t('app', 'N') : Yii::t('app', 'Y'),
            ],
            'sort_order',
            'des',
            [
                'attribute' => 'location',
                'format' => 'raw',
                'value' => $point['AsText(location)'],
            ],
            [
                'attribute' => 'content',
                'format' => 'raw',
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
