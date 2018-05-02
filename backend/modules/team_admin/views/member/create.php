<?php

use common\models\team\TeamMember;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model TeamMember */

$this->title = Yii::t('rcoa/team', 'Create Team Member');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', 'Teams'), 'url' => ['/teammanage/team/view', 'id' => $model->team_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-member-create">

    <h1><?= Html::encode($this->title.': '.$model->team->name) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'member' => $member,
        'isExist' => $isExist,
        'position' => $position,
    ]) ?>

</div>
