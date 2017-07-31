<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\team\searchs\TeamMemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="team-member-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'team_id') ?>

    <?= $form->field($model, 'u_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa/team', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa/team', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
