<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\teamwork\CourseAnnex */

$this->title = Yii::t('rcoa/teamwork', 'Create Course Annex');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Annexes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-annex-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
