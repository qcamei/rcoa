<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\multimedia\MultimediaCheck */

$this->title = Yii::t('rcoa/multimedia', 'Create Multimedia Check');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="multimedia-check-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
