<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemTaskProducer */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Task Producers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-task-producer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa/worksystem', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa/worksystem', 'Delete'), ['delete', 'id' => $model->id], [
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
            'worksystem_task_id',
            'team_member_id',
            'index',
            'is_delete',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
