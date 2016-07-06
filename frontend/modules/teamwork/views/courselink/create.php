<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\teamwork\CourseLink */

$this->title = Yii::t('rcoa/teamwork', 'Create Course Link');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-link-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
