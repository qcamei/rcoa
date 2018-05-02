<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\team\TeamMember */

$this->title = Yii::t('rcoa/team', 'Update Team Member');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', 'Teams'), 'url' => ['/teammanage/team/index']];
$this->params['breadcrumbs'][] = ['label' => $model->team->name, 'url' => ['/teammanage/team/view', 'id' => $model->team_id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="team-member-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'team' => $team,
        'member' => $member,
        'isExist' => $isExist,
        'position' => $position,
    ]) ?>

</div>
