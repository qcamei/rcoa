<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\Link */

$this->title = Yii::t('rcoa/framework', 'Update Link') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/framework', 'Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="link-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'phases' => $phases,
    ]) ?>

</div>
