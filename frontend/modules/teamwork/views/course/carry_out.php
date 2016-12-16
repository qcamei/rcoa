<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use wskeee\utils\DateUtil;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
    
/* @var $model CourseManage */
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">完成该开发任务</h4>
</div>
<div class="modal-body">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'form-carry_out', 
            'class'=>'form-horizontal',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-2 col-md-2 control-label',
                'style'=>[
                    'color'=>'#999999',
                    'font-weight'=>'normal',
                    'padding-right' => '0'
                ]
            ],  
        ], 
    ]); ?>

    <?php 
        if($isComplete)
            echo Html::encode('当前进度必须为100%！');
        else{
            if(empty($model->course_ops))
                echo $form->field($model, 'course_ops')->widget(Select2::classname(), [
                        'data' => $producerList, 'options' => ['placeholder' => '请选择...']
                    ]);
            
            if(!$model->validate()){
                if(empty($model->video_length))
                    echo $form->field($model, 'video_length')->textInput(['value'=>  DateUtil::intToTime($model->video_length)])->hint('aaaa');

                if(empty($model->question_mete))
                    echo $form->field($model, 'question_mete')->widget(TouchSpin::classname(),  [
                            'pluginOptions' => [
                                'placeholder' => '题量 ...',
                                'min' => 0,
                                'max' => 999999,
                            ],
                        ]);

                if(empty($model->case_number))
                    echo $form->field($model, 'case_number')->widget(TouchSpin::classname(),  [
                            'pluginOptions' => [
                                'placeholder' => '案例数...',
                                'min' => 0,
                                'max' => 999999,
                            ],
                        ]);

                if(empty($model->activity_number))
                    echo $form->field($model, 'activity_number')->widget(TouchSpin::classname(),  [
                            'pluginOptions' => [
                                'placeholder' => '活动数 ...',
                                'min' => 0,
                                'max' => 999999,
                            ],
                        ]);
                                
                if(empty($model->path))
                    echo $form->field($model, 'path')->textInput(['placeholder' => '课程存储服务器路径...']);
            }else
                echo Html::encode('是否确定要完成该课程开发！');

            echo Html::activeHiddenInput($model, 'real_carry_out', ['value' => date('Y-m-d H:i', time())]);
            echo Html::activeHiddenInput($model, 'status', ['value' => CourseManage::STATUS_CARRY_OUT]);
        }
    ?>
    
    <?php ActiveForm::end(); ?>

</div>
<?php
    echo Html::beginTag('div', ['class' => 'modal-footer']);
    if(!$isComplete)
        echo Html::button('确认', ['id' => 'carry_out-save', 'class' => 'btn btn-primary']);
    echo Html::endTag('div');
?>


<script type="text/javascript">
    /** 移交操作 提交表单 */
    $("#carry_out-save").click(function()
    {
        $('#form-carry_out').submit();       
    });
</script>

<?php
    TwAsset::register($this);
?>