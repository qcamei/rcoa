<?php

use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t(null, "{edit}{$title}：{$model->name}}", [
    'edit' => Yii::t('app', 'Edit'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="mcbs-update-activity mcbs mcbs-activity">

    <?= $this->render('activity_form',[
        'model' => $model,
        'actiType' => $actiType,
        'files' => $file,
    ]) ?>

</div>

<?php
$js = 
<<<JS
        
    /** 提交表单 */
    $("#submitsave").click(function(){
        $("#form-couframe").submit();
    });  
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>