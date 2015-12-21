<?php

use backend\modules\expert\models\ExpertCreateForm;
use common\models\expert\ExpertType;
use common\models\User;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model ExpertCreateForm */
/* @var $form ActiveForm */
?>

<div class="expert-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?php if($model->isNewRecord): ?>
    <?php echo $form->field($model, 'username')->textInput(['maxlength'=>32]); ?>
    <?php else : ?>
    <?php echo $form->field($model, 'username')->textInput(['maxlength'=>32,'readonly'=>'']); ?>
    <?php endif; ?>
    
    <?= $form->field($model, 'nickname')->textInput(['maxlength'=>32]); ?>
    
    <?= $form->field($model, 'type')->dropDownList(ArrayHelper::map(ExpertType::find()->all(),'id','name'),['prompt' => '请选择...']) ?>
    
    <?= $form->field($model, 'sex')->radioList([User::SEX_MALE => '男',  User::SEX_WOMAN => '女']); ?>
    
    <?= $form->field($model, 'phone')->textInput(['minlength'=>6,'maxlength'=>20]); ?>
    
    <?= $form->field($model, 'email')->textInput(['maxlength' => 200]) ?>
    
    <?= $form->field($model, 'personal_image')->fileInput() ?>
    
    <?=
    $form->field($model, 'birth')->widget(DatePicker::classname(), [
        'readonly' => true,
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy',
            'minViewMode' => 2,
        ]
    ]);
    ?>
    echo $form->field($model, 'date_1')->

    <?= $form->field($model, 'job_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'job_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'level')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'employer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'attainment')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
