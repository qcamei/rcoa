<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\workitem\WorkitemType */

$this->title = Yii::t('rcoa/workitem', 'Update Workitem Type') .':'. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/workitem', 'Workitem Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="workitem-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
