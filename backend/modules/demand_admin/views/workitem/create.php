<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandWorkitemTemplate */

$this->title = Yii::t('rcoa/demand', 'Create Demand Workitem Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Workitem Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-workitem-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'templateTypes' => $templateTypes,
        'workitemTypes' => $workitemTypes,
        'workitems' => $workitems,
    ]) ?>

</div>
