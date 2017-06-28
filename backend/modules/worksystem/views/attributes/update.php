<?php

use common\models\worksystem\WorksystemAttributes;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model WorksystemAttributes */

$this->title = Yii::t('rcoa/worksystem', 'Update Worksystem Attributes') .'：'. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update') .'：'. $model->name;
?>
<div class="worksystem-attributes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
