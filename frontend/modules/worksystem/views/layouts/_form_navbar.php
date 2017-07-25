<?php

use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\helpers\Html;
use yii\web\View;
    
/* @var $model WorksystemTask */

?>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), isset($params) ? $params : null, ['class' => 'btn btn-default']) ?>
        
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
    $('#submit').click(function()
    {
        var value = $('#task_type_id-worksystemtask-task_type_id').val();
        //var value = $('input[name="WorksystemAddAttributes[value]"]').val();
        //console.log(value);
        if(value == ''){
            $('.myModal').modal("show");
            //$('.worksystem-add-attributes-form .help-block').html('不能为空');
        }
        else
            $('#worksystem-task-form').submit();
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>