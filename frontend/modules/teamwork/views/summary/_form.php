<?php

use common\models\teamwork\CourseSummary;
use common\widgets\ueditor\UeditorAsset;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model CourseSummary */
/* @var $form ActiveForm */
?>

<div class="course-summary-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'course-summary-form',
            'class'=>'form-horizontal',
        ],
    ]) ?>

    <?php
        if(!$model->isNewRecord)
            echo '<span style="color:#ccc">时间：'.date('Y-m-d H:i', $model->created_at).'</span>'
    ?>
     <?= $form->field($model, 'content')->textarea([
            'id' => 'container', 
            'type' => 'text/plain', 
            'style' => 'width:100%; height:400px;',
            'placeholder' => '课程总结...'
    ])->label('') ?>
    
    <?php ActiveForm::end(); ?>

</div>


<?php

 $js =
<<<JS
    $('#container').removeClass('form-control');
    var ue = UE.getEditor('container', {toolbars:[
        [
            'fullscreen', 'source', '|', 'undo', 'redo', '|',  
            'bold', 'italic', 'underline','fontborder', 'strikethrough', 'removeformat', 'formatmatch', '|', 
            'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'paragraph', 'fontfamily', 'fontsize', '|',
            'justifyleft', 'justifyright' , 'justifycenter', 'justifyjustify', '|',
            'simpleupload', 'horizontal'
        ]
    ]});
    ue.ready(function(){
        ue.setContent('$weekly');
    });
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 

<?php
    UeditorAsset::register($this);
?>