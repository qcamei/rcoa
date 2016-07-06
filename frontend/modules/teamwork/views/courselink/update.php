<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\teamwork\CourseLink */

$this->title = Yii::t('rcoa/teamwork', 'Update {modelClass}: ', [
    'modelClass' => 'Course Link',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/teamwork', 'Update');
?>
<div class="course-link-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
