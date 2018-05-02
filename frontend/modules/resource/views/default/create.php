<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\resource\Resource */

$this->title = Yii::t('rcoa/resource', 'Create Resource');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/resource', 'Resources'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resource-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
