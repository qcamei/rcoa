<?php

use common\models\need\NeedTask;
use common\widgets\webuploader\WebUploaderAsset;
use frontend\modules\need\assets\ModuleAssets;
use kartik\datecontrol\DateControl;
use kartik\slider\Slider;
use kartik\widgets\Select2;
use yii\data\ArrayDataProvider;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model NeedTask */
/* @var $form ActiveForm */

ModuleAssets::register($this);

?>

<div class="need-task-form">

    <?php $form = ActiveForm::begin([
        'options'=>['id' => 'need-task-form', 'class'=>'form-horizontal'],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-11 col-md-11\">{input}</div>\n<div class=\"col-lg-11 col-md-11\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 control-label form-label',
            ],  
        ], 
    ]); ?>

    <div class="col-xs-12 frame">
        <div class="col-xs-12 title prompt"><i class="fa fa-file-text"></i><span>基本信息</span></div>
    </div>
    
    <?= $form->field($model, 'business_id')->widget(Select2::class, [
        'data' => [], 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'layer_id')->widget(Select2::class, [
        'data' => [], 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'profession_id')->widget(Select2::class, [
        'data' => [], 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'course_id')->widget(Select2::class, [
        'data' => [], 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'task_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'level')->radioList(['普通', '加急'], [
        'itemOptions'=>[
            'labelOptions'=>[
                'style'=>['margin'=>'5px 30px 5px 0']
            ]
        ],     
    ]) ?>
    
    <?= $form->field($model, 'need_time', [
        'template' => "{label}\n<div class=\"col-lg-4 col-md-4\">{input}</div>\n<div class=\"col-lg-4 col-md-4\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 col-md-1 control-label form-label'],
    ])->widget(DateControl::class,[
        'type'=> DateControl::FORMAT_DATETIME,
        'displayFormat' => 'yyyy-MM-dd H:i',
        'saveFormat' => 'yyyy-MM-dd H:i',
        'ajaxConversion'=> true,
        'autoWidget' => true,
        'readonly' => true,
        'options' => [
            'pluginOptions' => ['autoclose' => true,],
        ],
    ]) ?>
    
    <?= $form->field($model, 'performance_percent', [
        'template' => "{label}\n<div class=\"col-lg-4 col-md-4\">{input}</div>\n<div class=\"col-lg-4 col-md-4\">{error}</div>"
    ])->widget(Slider::class, [
        //'value' => $model->bonus_proportion,
        'sliderColor'=>Slider::TYPE_INFO,
        'handleColor'=>Slider::TYPE_PRIMARY,
        'pluginOptions'=>[
            'min' => 0.05,
            'max'=> 0.1,
            'precision'=>2,
            'handle'=>'square',
            'step'=>0.01,
            'tooltip'=>'always',
            'formatter'=>new JsExpression("function(val) { 
                return Math.round(val * 100) + '%';
            }")
        ],
    ]); ?>

    <?= $form->field($model, 'audit_by', [
        'template' => "{label}\n<div class=\"col-lg-4 col-md-4\">{input}</div>\n<div class=\"col-lg-4 col-md-4\">{error}</div>"
    ])->widget(Select2::class, [
        'data' => [], 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'plan_outsourcing_cost', [
        'template' => "{label}\n<div class=\"col-lg-4 col-md-4\">{input}</div>\n<div class=\"col-lg-4 col-md-4\">{error}</div>"
    ])->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'des')->textarea(['rows' => 3, 'value' => $model->isNewRecord ? '无' : $model->des]) ?>
    
    <div class="col-xs-12 frame">
        <div class="col-xs-12 title prompt"><i class="glyphicon glyphicon-tasks"></i><span>开发内容</span></div>
    </div>
    
    <?= $this->render('/content/index', [
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->contents,
        ]),
    ]) ?>
    
    <div class="col-xs-12 frame">
        <div class="col-xs-12 title prompt"><i class="fa fa-paperclip"></i><span>附件</span></div>
    </div>

    <div class="form-group field-attachments-upload_file_id">
        <div id="attachments" class="col-lg-12 col-md-12">
            <div id="attachments-container" class="col-lg-12 col-md-12"></div>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

<?php
//获取flash上传组件路径
$swfpath = $this->assetManager->getPublishedUrl(WebUploaderAsset::register($this)->sourcePath);
//获取已上传文件
$attFiles = json_encode([]);
$csrfToken = Yii::$app->request->csrfToken;
$app_id = Yii::$app->id ;
$js = 
<<<JS
    window.attachmentUploader;
    //加载文件上传  
    window.onloadUploader = function () {
        require(['euploader'], function (euploader) {
            //公共配置
            var config = {
                swf: "$swfpath" + "/Uploader.swf",
                // 文件接收服务端。
                server: '/webuploader/default/upload',
                //检查文件是否存在
                checkFile: '/webuploader/default/check-file',
                //分片合并
                mergeChunks: '/webuploader/default/merge-chunks',
                //自动上传
                auto: false,
                //开起分片上传
                chunked: true,
                formData: {
                    _csrf: "$csrfToken",
                    //指定文件上传到的应用
                    app_id: "$app_id",
                    //同时创建缩略图
                    makeThumb: 1
                }

            };
            //附件配置
            var config2 = $.extend({
                // 上传容器
                container: '#attachments-container',
                //指定选择文件的按钮容器
                pick: {
                    id:  '#attachments .euploader-btns > div',
                },
            }, config);
            //附件
            window.attachmentUploader = new euploader.Uploader(config2, euploader.FilelistView);
            window.attachmentUploader.addCompleteFiles($attFiles);
        });
    }
    /**
    * 上传文件完成才可以提交
    * @return {uploader.isFinish}
    */
    function tijiao() {
       //uploader,isFinish() 是否已经完成所有上传
       return window.attachmentUploader.isFinish();
    }
    /**
    * 侦听模态框关闭事件，销毁 uploader 实例
    *
    */
    $('.myModal').on('hidden.bs.modal',function(){
        $('.myModal').off('hidden.bs.modal');
        window.attachmentUploader.destroy();
    });    
   
JS;
    $this->registerJs($js,  View::POS_READY);
?>