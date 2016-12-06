<?php

use common\models\product\ProductDetails;
use common\widgets\ueditor\UeditorAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model ProductDetails */
/* @var $form ActiveForm */
?>

<div class="product-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'details')->textarea([
            'id' => 'container', 
            'type' => 'text/plain', 
            'style' => 'width:100%; height:450px;'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    <?= Html::activeHiddenInput($model, 'product_id', ['value' => $productId]) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js =   
<<<JS
    $('#container').removeClass('form-control');
    var ue = UE.getEditor('container');
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 

<?php
    UeditorAsset::register($this);
?>