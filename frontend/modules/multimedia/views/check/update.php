<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\multimedia\MultimediaCheck */

$this->title = Yii::t('rcoa/multimedia', 'Update {modelClass}: ', [
    'modelClass' => 'Multimedia Check',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/multimedia', 'Update');
?>
<div class="multimedia-check-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
