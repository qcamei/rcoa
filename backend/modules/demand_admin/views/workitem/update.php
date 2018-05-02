<?php

use common\models\demand\DemandWorkitemTemplate;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model DemandWorkitemTemplate */

$this->title = Yii::t('rcoa/demand', 'Update Demand Workitem Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Workitem Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->workitem->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="demand-workitem-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'templateTypes' => $templateTypes,
        'workitemTypes' => $workitemTypes,
        'workitems' => $workitems,
    ]) ?>

</div>
