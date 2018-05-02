<?php

use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\helpers\Html;
use yii\web\View;
    
/* @var $model WorksystemTask */

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
    window.first = 0;
    $('#submit').click(function()
    {
        if(window.first == 0){
            var dataAdd =  $("#worksystemtask-course_id").attr("data-add");
            var tablelen = $('.table tbody tr').length;
            $("#worksystemtask-task_type_id").attr("disabled", false);
            //判断内容信息是否为空
            if(tablelen <= 0)
                $('#prompt').html('<span class="error-warn">内容信息不能为空！</span>');
        
            if(dataAdd == "true" && tablelen > 0){
                $('#worksystem-task-form').submit();
                window.first = 1;
            }
        }
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>