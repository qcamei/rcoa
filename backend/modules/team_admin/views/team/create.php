<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\team\Team */

$this->title = Yii::t('rcoa/team', 'Create Team');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', 'Teams'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teamType' => $teamType,
    ]) ?>

</div>
