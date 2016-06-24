<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\team\TeamMember */

$this->title = Yii::t('rcoa/team', 'Create Team Member');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', 'Team Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-member-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'member' => $member,
    ]) ?>

</div>
