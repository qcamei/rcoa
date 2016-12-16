<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandAcceptance */

$this->title = Yii::t('rcoa/demand', 'Create Demand Acceptance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Acceptances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-acceptance-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
