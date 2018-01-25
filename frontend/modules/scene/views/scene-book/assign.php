<?php

use common\models\scene\SceneBook;
use frontend\modules\scene\assets\SceneAsset;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
    
/* @var $model SceneBook */
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= Html::encode('指派摄影师') ?></h4>
        </div>
        <div class="modal-body scene">

           <?php $form = ActiveForm::begin(['options'=>['id' => 'scene-book-form','class'=>'form-horizontal']]); ?>

            <div class="form-group field-scenebookuser-user_id">
                <label class="col-lg-2 col-md-2 control-label form-label" for="scenebookuser-user_id">
                    <?= Yii::t('app', 'Shoot Man') ?>
                </label>
                <div class="col-lg-10 col-md-10">
                    
                    <?= Select2::widget([
                            'id' => 'scenebookuser-user_id',
                            'name' => 'SceneBookUser[user_id][]',
                            'value' => array_keys($existSceneBookUser),
                            'data' => $createSceneBookUser,
                            'maintainOrder' => true,    //无序排列
                            'hideSearch' => true,
                            'options' => [
                                'placeholder' => '请选择制作人...',
                                'multiple' => true,     //设置多选
                            ],
                            'toggleAllSettings' => [
                                'selectLabel' => '<i class="glyphicon glyphicon-ok-circle"></i> 添加全部',
                                'unselectLabel' => '<i class="glyphicon glyphicon-remove-circle"></i> 取消全部',
                                'selectOptions' => ['class' => 'text-success'],
                                'unselectOptions' => ['class' => 'text-danger'],
                            ],
                            'pluginOptions' => [
                                'tags' => false,
                                'maximumInputLength' => 10,
                                'allowClear' => true,
                            ],
                            'pluginEvents' => [
                                'change' => 'function(){ select2Log();}'
                            ]
                        ])
                    ?>
                </div>
                <div class="col-lg-10 col-md-10"><div class="help-block"></div></div>
            </div>

            <?= Html::activeHiddenInput($model, 'status', ['value' => SceneBook::STATUS_SHOOTING]) ?>
            
            <?php ActiveForm::end(); ?>

        </div>
        <div class="modal-footer">
            <?= Html::button(Yii::t('app', 'Close'), ['id'=>'submitsave','class'=>'btn btn-danger',
                'data-dismiss'=>'modal','aria-label'=>'Close']) ?>
            <?= Html::button(Yii::t('app', 'Submit'), ['id'=>'submitsave','class'=>'btn btn-primary',
                'onclick' => 'submitsave();']) ?>
        </div>
    </div>
</div>

<?php
//机位数
$camera_count = $model->camera_count;
$js = 
<<<JS
    //设置第一个选择边框为蓝色
    window.select2Log = function(){
        $("ul.select2-selection__rendered").find("li.select2-selection__choice").eq(0).css({border:"1px solid blue"});
    }
    //提交表单
    window.submitsave = function(){
        var cameraCount = $camera_count;    //机位数
        var str = selectLength();
        if(str.length < cameraCount){
            $(".field-scenebookuser-user_id").addClass("has-error");
            $(".field-scenebookuser-user_id .help-block").html("所选的【摄影师】少于【机位数】");
        }else if(str.length > cameraCount){
            $(".field-scenebookuser-user_id").addClass("has-error");
            $(".field-scenebookuser-user_id .help-block").html("所选的【摄影师】大于【机位数】");
        }
        clearInterval();
        setTimeout(function(){
            $("#scene-book-form").submit();
        }, 300);
    };
    //组装选中的摄影师人数
    function selectLength(){
        var select = document.getElementById("scenebookuser-user_id");
        var strLength = [];
        for(i=0;i<select.length;i++){
            if(select.options[i].selected){
                strLength.push(select[i].value);
            }
        }
        return strLength;
    }    
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>