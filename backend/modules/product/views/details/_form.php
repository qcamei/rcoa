<?php

use common\models\product\ProductDetails;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model ProductDetails */
/* @var $form ActiveForm */
?>


<div class="product-details-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'id' => 'product-details-form',
            //'action' => '#',
        ],
    ]); ?>

    <?= $form->field($model, 'details[]')->widget(FileInput::className(), [
        'options'=>[
            'multiple'=>true,
            'webkitdirectory' => true,
        ],
        
        'pluginOptions' => [
            /*'uploadUrl' => Url::to(['file-upload']),
            'uploadExtraData' => [
                'name' => 'document.getElementsByClassName("file-preview-frame").setAttribute("title")'
            ],*/
            'showUpload' => false,
            'dropZoneTitle' => '支持多文件同时上传',
            'maxFileCount' => 99999,
            'allowedPreviewTypes' => null,
        ]
    ]) ?>

    
    <?= Html::activeHiddenInput($model, 'product_id', ['value' => $productId]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>




<?php
$js =   
<<<JS
   $('.fileinput-upload-button').attr('type', 'button');
   $('.fileinput-upload-button').click(function(){
       $.post('product/details/file-upload', $('#product-details-form').serialize(),function(){
           
        });
   });
    
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 

<?php
    //UeditorAsset::register($this);
?>