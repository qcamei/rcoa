<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\team\Team */

$this->title = Yii::t('rcoa/team', 'Update Team'). ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', 'Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="team-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teamType' => $teamType,
    ]) ?>

</div>
