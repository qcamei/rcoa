<?php

use common\models\demand\DemandWeightTemplate;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model DemandWeightTemplate */

$this->title = Yii::t('rcoa/demand', 'Update Demand Weight Template') . $model->workitemType->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Weight Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->workitemType->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="demand-weight-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'workitemType' => $workitemType,
    ]) ?>

</div>
