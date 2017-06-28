<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemOperation */

$this->title = Yii::t('rcoa/worksystem', 'Create Worksystem Operation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Operations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-operation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
