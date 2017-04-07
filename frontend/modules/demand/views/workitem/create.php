<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandWorkitem */

$this->title = Yii::t('rcoa/demand', 'Create Demand Workitem');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Workitems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-workitem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
