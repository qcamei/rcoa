<?php

use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t(null, "{add}{$title}",[
    'add' => Yii::t('app', 'Add'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mcbs-create-activity mcbs mcbs-activity">
    
    <?= $this->render('activity_form',[
        'model' => $model,
        'actiType' => $actiType,
        'files' => $file,
    ]) ?>
    
</div>

<?php
$js = 
<<<JS
        
    /** 提交表单 
    $("#submitsave").click(function(){
        $("#form-activity").submit();
    });  */ 
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>