<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemTask */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-task-view">

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
            'item_type_id',
            'item_id',
            'item_child_id',
            'course_id',
            'task_type_id',
            'name',
            'level',
            'is_epiboly',
            'budget_cost',
            'reality_cost',
            'budget_bonus',
            'reality_bonus',
            'plan_end_time',
            'external_team',
            'status',
            'progress',
            'create_team',
            'create_by',
            'index',
            'is_delete',
            'created_at',
            'updated_at',
            'finished_at',
            'des:ntext',
        ],
    ]) ?>

</div>
