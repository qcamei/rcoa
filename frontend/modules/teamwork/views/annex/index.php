<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/teamwork', 'Course Annexes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-annex-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa/teamwork', 'Create Course Annex'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'course_id',
            'name',
            'path',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
