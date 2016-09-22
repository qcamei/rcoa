<?php

use common\models\multimedia\MultimediaTypeProportion;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model MultimediaTypeProportion */

$this->title = Yii::t('rcoa/multimedia', 'Create Multimedia Type Proportion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Content Type View'), 'url' => [
    'contenttype/view', 'id' => $contentType]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="multimedia-type-proportion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'contentType' => $contentType,
        'contentTypes' => $contentTypes
    ]) ?>

</div>
