<?php

use common\models\team\TeamCategoryMap;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model TeamCategoryMap */
/* @var $form ActiveForm */
?>

<div class="team-category-map-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_id')->widget(Select2::classname(), [
        'data' => $teamCategorys, 
        'options' => ['placeholder' => Yii::t('rcoa', 'Select Placeholder')]
    ])->label(Yii::t('rcoa/team', 'Category'))?>

    <?= $form->field($model, 'team_id')->widget(Select2::classname(), [
        'data' => $teams, 
        'options' => ['placeholder' => Yii::t('rcoa', 'Select Placeholder')]
    ])->label(Yii::t('rcoa/team', 'Team'))?>

    <?= $form->field($model, 'index')->textInput() ?>

    <?= $form->field($model, 'is_delete')->textInput(['maxlength' => true]) ?>
    
    <?= Html::hiddenInput('callback', "/teammanage/team-category/view?id=$model->category_id") ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
