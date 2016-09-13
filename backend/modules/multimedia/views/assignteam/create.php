<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\multimedia\MultimediaAssignTeam */

$this->title = Yii::t('rcoa/multimedia', 'Create Multimedia Assign Team');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Assign Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="multimedia-assign-team-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'team' => $team,
        'user' => $user,
    ]) ?>

</div>
