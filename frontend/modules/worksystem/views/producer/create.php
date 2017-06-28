<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemTaskProducer */

$this->title = Yii::t('rcoa/worksystem', 'Create Worksystem Task Producer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Task Producers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-task-producer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
