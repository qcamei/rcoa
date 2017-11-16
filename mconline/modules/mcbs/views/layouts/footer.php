<?php

use common\models\mconline\McbsCourse;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $model McbsCourse */
?>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('app', 'Back'), isset($params) ? $params : null, ['class' => 'btn btn-default']) ?>

        <?= Html::a(
                $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('rcoa', 'Update'),
                    'javascript:;', 
                        ['id' => 'submit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])
        ?>
    </div>
</div>

<?php
$js = <<<JS
        
    $('#submit').click(function(){
        $('#mcbs-form').submit();
    })   
    
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php 
    McbsAssets::register($this);
?>