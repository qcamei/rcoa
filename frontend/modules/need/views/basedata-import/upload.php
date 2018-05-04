<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $form ActiveForm */

$this->title = Yii::t('app', '{Import}{Basedata}',[
    'Import' => Yii::t('app', 'Import'),
    'Basedata' => Yii::t('app', 'Basedata'),
]);

?>

<div class="main import-form">
    <h3 style="margin-top: 0px">数据导入</h3>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <div class="form-group">
        <?= Html::fileInput('import-file') ?>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton('导入',['class' => 'btn btn-primary']) ?>
        <a class="btn btn-default" href="/filedata/need/基础数据导入模板.xlsx">下载模版</a>
    </div>
    
    <?php ActiveForm::end(); ?>
</div>

