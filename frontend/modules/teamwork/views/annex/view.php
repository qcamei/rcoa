<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\teamwork\CourseAnnex */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Annexes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-annex-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa/teamwork', 'Update'), ['update', 'id' => $model->course_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa/teamwork', 'Delete'), ['delete', 'id' => $model->course_id], [
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
            'course_id',
            'name',
            'path',
        ],
    ]) ?>

</div>
