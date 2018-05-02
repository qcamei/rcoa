<?php

use common\models\mconline\McbsActivityType;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model McbsActivityType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('null', '{Activity}{Type}{Administration}', [
        'Activity' => Yii::t('app', 'Activity'),
        'Type' => Yii::t('app', 'Type'),
        'Administration' => Yii::t('app', 'Administration'),
    ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcbs-activity-type-view">

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
            'des',
            [
                'attribute' => 'icon_path',
                'format' => 'raw',
                'value' => Html::img(MCONLINE_WEB_ROOT.$model->icon_path, ['width' => '40', 'height' => '40']),
            ],
            //'icon_path',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
