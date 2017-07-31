<?php

use common\models\worksystem\WorksystemAssignTeam;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model WorksystemAssignTeam */

$nickname = !empty($model->user_id) ? $model->user->nickname : '';

$this->title = Yii::t('rcoa/worksystem', 'Update Worksystem Assign Team').'：'.$nickname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Assign Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update').'：'.$nickname;
?>
<div class="worksystem-assign-team-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teams' => $teams,
        'users' => $users,
    ]) ?>

</div>
