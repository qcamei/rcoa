<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemAddAttributes */

$this->title = Yii::t('rcoa/worksystem', 'Update {modelClass}: ', [
    'modelClass' => 'Worksystem Add Attributes',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Add Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/worksystem', 'Update');
?>
<div class="worksystem-add-attributes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
