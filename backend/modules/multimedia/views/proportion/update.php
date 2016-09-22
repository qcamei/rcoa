<?php

use common\models\multimedia\MultimediaTypeProportion;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model MultimediaTypeProportion */

$this->title = Yii::t('rcoa/multimedia', 'Update Multimedia Type Proportion').': '.  date('Y-m', $model->created_at);
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Content Type View'), 'url' => [
    'contenttype/view', 'id' => $model->content_type]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="multimedia-type-proportion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'contentTypes' => $contentTypes
    ]) ?>

</div>
