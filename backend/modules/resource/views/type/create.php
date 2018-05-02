<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\resource\ResourceType */

$this->title = Yii::t('rcoa/resource', 'Create Resource Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/resource', 'Resource Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resource-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
