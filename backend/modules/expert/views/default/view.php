<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\expert\Expert */

$this->title = $model->u_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa', 'Experts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->u_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->u_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'u_id',
            'type',
            'birth',
            'job_title',
            'job_name',
            'level',
            'employer',
            'attainment:ntext',
        ],
    ]) ?>

</div>
