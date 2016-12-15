<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandTaskAuditor */

$this->title = Yii::t('rcoa/demand', 'Create Demand Task Auditor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Task Auditors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-task-auditor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teams' => $teams,
        'users' => $users,
    ]) ?>

</div>
