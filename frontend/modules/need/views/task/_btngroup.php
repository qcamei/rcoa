<?php

use yii\helpers\Html;
use yii\web\View;
    
?>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), isset($params) ? $params : null, ['class' => 'btn btn-default'])?>
        
        <?= Html::a(
                $model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'),
                'javascript:;', 
                ['id'=>'submit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
        ?>
    </div>
</div>

<?php
$js = 
<<<JS
    window.onloadUploader();    //加载文件上传      
    //提交表单    
    $('#submit').click(function(){
        $('#demand-task-form').submit();
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>