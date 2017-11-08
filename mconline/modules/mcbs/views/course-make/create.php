<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\mconline\McbsCourse */

$this->title = Yii::t('app', 'Create Mcbs Course');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcbs-course-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
