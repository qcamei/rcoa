<?php

use common\models\expert\ExpertProject;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model ExpertProject */
/* @var $form ActiveForm */
?>

<div class="expert-project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model, 'expert_id') ?>

    <?=
    $form->field($model, 'project_id')->widget(Select2::classname(), [
        'data' => $projects,
        'options' => ['placeholder' => '请选择项目 ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?=
    $form->field($model, 'start_time')->widget(DatePicker::classname(), [
        'readonly' => true,
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy/mm/dd',
        ]
    ]);
    ?>

    <?=
    $form->field($model, 'end_time')->widget(DatePicker::classname(), [
        'readonly' => true,
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy/mm/dd',
        ]
    ]);
    ?>

    <?= $form->field($model, 'cost')->textInput() ?>

    <?= $form->field($model, 'compatibility')->dropDownList(ExpertProject::$compatibilityMap) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Back'), ['/expert/default/view','id'=>$model->expert_id], ['class'=>'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
