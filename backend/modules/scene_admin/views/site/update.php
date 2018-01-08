<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\scene\SceneSite */

$this->title = Yii::t('app', '{Update}{Scene}: ', [
    'Update' => Yii::t('app', 'Update'),
    'Scene' => Yii::t('app', 'Scene'),
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Scene}{Administration}',[
    'Scene' => Yii::t('app', 'Scene'),
    'Administration' => Yii::t('app', 'Administration'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="scene-site-update">

    <h1><?php //Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'area' => $area,
        'manager' => $manager,
    ]) ?>

</div>
