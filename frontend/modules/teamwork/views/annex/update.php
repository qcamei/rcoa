<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\teamwork\CourseAnnex */

$this->title = Yii::t('rcoa/teamwork', 'Update {modelClass}: ', [
    'modelClass' => 'Course Annex',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Annexes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->course_id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/teamwork', 'Update');
?>
<div class="course-annex-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
