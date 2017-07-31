<?php

use common\models\team\TeamMember;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model TeamMember */
/* @var $form ActiveForm */
?>

<div class="team-member-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?php
        if(!$model->isNewRecord)
            echo $form->field($model, 'team_id')->widget(Select2::classname(), [
                'data' => $team, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
            ]);
    ?>

    <?= $form->field($model, 'u_id')->widget(Select2::classname(), [
        'data' => $member, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ]) ?>
    
    <?php 
        if(!$model->isNewRecord){
            echo $form->field($model, 'index')->widget(TouchSpin::classname(),  [
                    'pluginOptions' => [
                        'placeholder' => '顺序 ...',
                        'min' => -1,
                        'max' => 999999999,
                    ],
                ]);
        }
    ?>

    <?= $form->field($model, 'position_id')->widget(Select2::classname(), [
        'data' => $position, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ]) ?>

    <?= $form->field($model, 'is_leader')->radioList(TeamMember::$is_leaders)->label('') ?>
    
    <?= Html::hiddenInput('id', $model->id) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$js =   
<<<JS
    var isNewRecord = "$model->isNewRecord";
    var isExist = $isExist;
    if(isNewRecord && !isExist)     
        $("input:radio").eq(0).attr("checked",true);
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 