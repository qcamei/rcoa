<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\mconline\McbsActivityFile */

$this->title = Yii::t('app', 'Create Mcbs Activity File');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Activity Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcbs-activity-file-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
