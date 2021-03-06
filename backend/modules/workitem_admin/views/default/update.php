<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\workitem\Workitem */

$this->title = Yii::t('rcoa/workitem', 'Update Workitem') .':'. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/workitem', 'Workitems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="workitem-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
