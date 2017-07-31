<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\workitem\WorkitemCost */

$this->title = Yii::t('rcoa/workitem', 'Update Workitem Cost') .':'. $model->workitem->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/workitem', 'Workitem Costs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="workitem-cost-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'workitems' => $workitems
    ]) ?>

</div>
