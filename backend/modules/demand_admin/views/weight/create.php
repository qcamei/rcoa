<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandWeightTemplate */

$this->title = Yii::t('rcoa/demand', 'Create Demand Weight Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Weight Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-weight-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'workitemType' => $workitemType,
    ]) ?>

</div>
