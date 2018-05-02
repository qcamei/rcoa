<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\multimedia\MultimediaContentType */

$this->title = Yii::t('rcoa/multimedia', 'Create Multimedia Content Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Content Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="multimedia-content-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
