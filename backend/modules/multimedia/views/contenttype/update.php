<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\multimedia\MultimediaContentType */

$this->title = Yii::t('rcoa/multimedia', 'Update Multimedia Content Type').': '.$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Content Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="multimedia-content-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
