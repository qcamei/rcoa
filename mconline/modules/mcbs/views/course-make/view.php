<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\mconline\McbsCourse */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcbs-course-view">

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
            'item_type_id',
            'item_id',
            'item_child_id',
            'course_id',
            'create_by',
            'status',
            'is_publish',
            'publish_time',
            'close_time',
            'des:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
