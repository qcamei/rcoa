<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandWorkitemTemplateType */

$this->title = Yii::t('rcoa/demand', 'Create Demand Workitem Template Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Workitem Template Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-workitem-template-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
