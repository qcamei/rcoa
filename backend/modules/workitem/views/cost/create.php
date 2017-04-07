<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\workitem\WorkitemCost */

$this->title = Yii::t('rcoa/workitem', 'Create Workitem Cost');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/workitem', 'Workitem Costs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workitem-cost-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'workitems' => $workitems
    ]) ?>

</div>
