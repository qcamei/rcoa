<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemAttributes */

$this->title = Yii::t('rcoa/worksystem', 'Create Worksystem Attributes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-attributes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'taskTypes' => $taskTypes,
    ]) ?>

</div>
