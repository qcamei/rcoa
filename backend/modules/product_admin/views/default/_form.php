<?php

use common\models\product\Product;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Product */
/* @var $form ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <?= $form->field($model, 'type')->widget(Select2::classname(), [
        'data' => $productType, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ]) ?>
    
    <?= $form->field($model, 'level')->widget(Select2::classname(), [
        'data' => Product::$levelName,  'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ]) ?>
    
    <?php
        if(!$model->isNewRecord && ($model->level != Product::CLASSIFICATION || $model->parent_id != null))
            echo $form->field($model, 'parent_id')->widget(Select2::classname(), [
                'data' => $classification,  'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
            ]);
    ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'unit_price', ['options'=> [
        'style' => $model->level != Product::GOODS ? 'display: none;' : 'display: block'
    ]])->textInput() ?>

    <?= $form->field($model, 'currency', ['options'=> [
       'style' => $model->level != Product::GOODS ? 'display: none;' : 'display: block'
    ]])->textInput() ?>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'des')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

<?php

$js =   
<<<JS
    var myselect = document.getElementById("product-level");
    console.log($('#product-level'));
    myselect.onchange = function(){
        switch (myselect.selectedIndex)
        {
            case 1:
                $('.field-product-unit_price').fadeOut();
                $('.field-product-currency').fadeOut();
            break;
            case 2:
                $('.field-product-unit_price').fadeIn();
                $('.field-product-currency').fadeIn();
            break;
        }
    }
    
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 
