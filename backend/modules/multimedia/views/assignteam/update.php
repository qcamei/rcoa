<?php

use common\models\multimedia\MultimediaAssignTeam;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model MultimediaAssignTeam */

$this->title = Yii::t('rcoa/multimedia', 'Update Multimedia Assign Team').': '.$model->team->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Assign Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->team->name, 'url' => ['view', 'team_id' => $model->team_id, 'u_id' => $model->u_id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="multimedia-assign-team-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'team' => $team,
        'user' => $user,
    ]) ?>

</div>
