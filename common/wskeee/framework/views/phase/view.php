<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\Phase */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/framework', 'Phases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phase-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa/framework', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa/framework', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/framework', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'weights',
            'progress',
            'create_by',
        ],
    ]) ?>

</div>
