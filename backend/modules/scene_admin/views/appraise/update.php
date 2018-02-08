<?php

use common\models\scene\SceneAppraise;
use common\models\scene\SceneBookUser;
use yii\web\View;

/* @var $this View */
/* @var $model SceneAppraise */

$this->title = Yii::t('rcoa', 'Update {modelClass}: ', [
    'modelClass' => 'Shoot Appraise',
]) . ' ' . SceneBookUser::$roleName[$model->role];
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa', 'Shoot Appraises'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="shoot-appraise-update">

    <?= $this->render('_form', [
        'model' => $model,
        'roles' => $roles,
        'questions' => $questions,
    ]) ?>

</div>
