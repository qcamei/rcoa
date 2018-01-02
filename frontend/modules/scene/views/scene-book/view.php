<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\scene\SceneBook */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Scene Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-book-view">

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
            'site_id',
            'date',
            'time_index:datetime',
            'status',
            'business_id',
            'level_id',
            'profession_id',
            'course_id',
            'lession_time:datetime',
            'content_type',
            'shoot_mode',
            'is_photograph',
            'camera_count',
            'start_time',
            'remark',
            'is_transfer',
            'teacher_id',
            'booker_id',
            'created_by',
            'created_at',
            'updated_at',
            'ver',
        ],
    ]) ?>

</div>
