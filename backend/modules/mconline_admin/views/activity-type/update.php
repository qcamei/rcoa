<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\mconline\McbsActivityType */

$this->title = Yii::t('null', '{Update}{Activity}{Type}: ', [
            'Update' => Yii::t('app', 'Update'),
            'Activity' => Yii::t('app', 'Activity'),
            'Type' => Yii::t('app', 'Type'),
        ]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('null', '{Activity}{Type}{Administration}', [
        'Activity' => Yii::t('app', 'Activity'),
        'Type' => Yii::t('app', 'Type'),
        'Administration' => Yii::t('app', 'Administration'),
    ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="mcbs-activity-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
