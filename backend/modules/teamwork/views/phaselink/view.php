<?php

use common\models\teamwork\PhaseLink;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model PhaseLink */

$this->title = $model->phases_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Phase Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phase-link-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa/teamwork', 'Update'), ['update', 'phases_id' => $model->phases_id, 'link_id' => $model->link_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa/teamwork', 'Delete'), ['delete', 'phases_id' => $model->phases_id, 'link_id' => $model->link_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/teamwork', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'phases_id',
            'link_id',
            'total',
            'completed',
            'progress',
            'create_by',
        ],
    ]) ?>

</div>
