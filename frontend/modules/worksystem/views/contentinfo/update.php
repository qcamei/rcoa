<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemContentinfo */

$this->title = Yii::t('rcoa/worksystem', 'Update {modelClass}: ', [
    'modelClass' => 'Worksystem Contentinfo',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Contentinfos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/worksystem', 'Update');
?>
<div class="worksystem-contentinfo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
