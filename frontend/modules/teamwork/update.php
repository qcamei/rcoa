<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\teamwork\CourseSummary */

$this->title = Yii::t('rcoa/teamwork', 'Update {modelClass}: ', [
    'modelClass' => 'Course Summary',
]) . $model->course_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Summaries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->course_id, 'url' => ['view', 'id' => $model->course_id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/teamwork', 'Update');
?>
<div class="course-summary-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
