<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandTaskAuditor */

$this->title = $model->team->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Task Auditors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-task-auditor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'team_id' => $model->team_id, 'u_id' => $model->u_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'team_id' => $model->team_id, 'u_id' => $model->u_id], [
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
            [
                'attribute' => 'team_id',
                'value' => $model->team->name,
            ],
            [
                'attribute' => 'u_id',
                'value' => $model->taskUser->nickname,
            ],
        ],
    ]) ?>

</div>
