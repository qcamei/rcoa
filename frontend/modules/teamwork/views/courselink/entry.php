<?php

use common\models\teamwork\CourseLink;
use common\models\teamwork\Link;
use kartik\slider\Slider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this View */
/* @var $model CourseLink */
/* @var $form ActiveForm */
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h3 style="margin:0; padding:0"><?= $model->link->name?></h3>
</div>

<div class="entry-link-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'entry-manage-form',
            'class'=>'form-horizontal ',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10 \">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 control-label',
                'style'=>[
                    'color'=>'#999999',
                    'font-weight'=>'normal', 
                    'padding-left' => 0,
                    'padding-right' => 0,
                ]
            ],  
        ], 
    ]); ?>

    <?= $form->field($model, 'total')->textInput([
        $model->link->type === Link::AMOUNT ? '' : 'disabled' => 'disabled']) ?>

    <?= $form->field($model, 'completed')->widget(Slider::classname(), [
        'pluginConflict' => true,
        'value'=>2.54,
        'sliderColor'=>Slider::TYPE_WARNING,
        'handleColor'=>Slider::TYPE_WARNING,
        'pluginOptions'=>[
            'min'=>0,
            'max'=>20,
            'step'=>1,
            'handle'=>'triangle',
            'tooltip'=>'always',
        ],
        //'options'=>['disabled'=>true]
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>

<div class="modal-footer">
    <?= Html::submitButton('保存',['id' => 'submit', 'class' => 'btn btn-primary'])?>

</div>

            