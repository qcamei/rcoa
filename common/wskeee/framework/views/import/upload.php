<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
/* @var $this View */
/* @var $form ActiveForm */
?>
<h1>课程数据导入</h1>

<div class="import-form">
    
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <div class="form-group">
        <?= Html::fileInput('import-file') ?>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton('导入',['class' => 'btn btn-primary']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>

