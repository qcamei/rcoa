<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\expert\Expert */

$this->title = Yii::t('rcoa/demand', 'Update {modelClass}: ', [
    'modelClass' => 'Expert',
]) . $model->u_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Experts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->u_id, 'url' => ['view', 'id' => $model->u_id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/demand', 'Update');
?>
<div class="expert-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
