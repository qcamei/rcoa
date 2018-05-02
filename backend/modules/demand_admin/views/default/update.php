<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandTaskAuditor */

$this->title = Yii::t('rcoa/demand', 'Update Demand Task Auditor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Task Auditors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->team_id, 'url' => ['view', 'team_id' => $model->team_id, 'u_id' => $model->u_id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="demand-task-auditor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teams' => $teams,
        'users' => $users,
    ]) ?>

</div>
