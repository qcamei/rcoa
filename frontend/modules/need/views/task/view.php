<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\need\NeedTask */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="need-task-view">

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
            'company_id',
            'business_id',
            'layer_id',
            'profession_id',
            'course_id',
            'task_name',
            'level',
            'performance_percent',
            'need_time',
            'finish_time',
            'status',
            'is_del',
            'save_path',
            'plan_content_cost',
            'plan_outsourcing_cost',
            'reality_content_cost',
            'reality_outsourcing_cost',
            'des',
            'receive_by',
            'audit_by',
            'created_by',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
