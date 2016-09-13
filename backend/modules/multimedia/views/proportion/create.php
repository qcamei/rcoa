<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\multimedia\MultimediaProportion */

$this->title = Yii::t('rcoa/multimedia', 'Create Multimedia Proportion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Proportions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="multimedia-proportion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
