<?php

use common\models\mconline\McbsCourseActivity;
use common\widgets\webuploader\WebUploaderAsset;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model McbsCourseActivity */
/* @var $form ActiveForm */
?>

<div class="mcbs-activity-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'form-activity',
            'class'=>'form-horizontal',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 form-label',
            ],  
        ], 
    ]); ?>

    <div class="form-group field-mcbscourseactivity-type_id">
        <label class="col-lg-1 col-md-1 form-label" for="mcbscourseactivity-type_id">
            <?= Yii::t('app', 'Type') ?>
        </label>
        <div class="col-lg-11 col-md-11">
            <?php foreach($actiType as $item): ?>
            <div class="actitype <?= (!$model->isNewRecord && $model->type_id == $item['id'] ? 'active': null) ?>">
                <?= Html::img([$item['icon_path']],['class'=>'acticon']) ?>
                <p class="actname" data-key="<?= $item['id'] ?>"><?= $item['name']; ?></p>
            </div>
            <?php endforeach; ?>
            <?= Html::activeHiddenInput($model, 'type_id'); ?>
        </div>
        <div class="col-lg-11 col-md-11"><div class="help-block"></div></div>
    </div>
        
    <?= $form->field($model, 'name')->textInput(['placeholder'=>'请输入...']) ?>

    <?= $form->field($model, 'des')->textarea(['rows'=>3,'value'=>$model->isNewRecord?'无':$model->des]) ?>
    
    <div class="form-group field-mcbsactivityfile-file_id">
        <div id="uploader" class="col-lg-12 col-md-12">
            <label class="col-lg-1 col-md-1 form-label" style="padding-left:0;font-size:14px;" for="mcbsactivityfile-file_id">文件上传：</label>
            <div id="uploader-container" class="col-lg-10 col-md-10"></div>
            <div class="col-lg-10 col-md-10"><div class="help-block"></div></div>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

<?php

//获取flash上传组件路径
$swfpath = $this->assetManager->getPublishedUrl(WebUploaderAsset::register($this)->sourcePath);
//获取已上传文件
$files = json_encode($files);
$csrfToken = Yii::$app->request->csrfToken;
$app_id = Yii::$app->id ;


$js = 
<<<JS
    
    //选择类型
    $(".actitype").click(function(){
        $(".actitype").removeClass("active");
        $(this).addClass("active");
        $(".field-mcbscourseactivity-type_id").removeClass("has-error");
        $(".field-mcbscourseactivity-type_id .help-block").html("");
        $("#mcbscourseactivity-type_id").val($(this).children("p").attr("data-key"));
    });
    //加载文件上传  
    var uploader;
    uploader = new Wskeee.Uploader({
        // 文件接收服务端。
        server: '/webuploader/default/upload',
        //检查文件是否存在
        checkFile: '/webuploader/default/check-file',
        //分片合并
        mergeChunks: '/webuploader/default/merge-chunks',
        //flash上传组件
        swf: '$swfpath' + '/Uploader.swf',
        // 上传容器
        container: '#uploader-container',
        //自动上传
        auto: false,
        //每次上传都会传到服务器的固定参数
        formData: {
            _csrf: "$csrfToken",
            //指定文件上传到的应用
            dir_path: 'mcoline',
            app_id: "$app_id",
            //debug: 1,
        }
    });
    uploader.addCompleteFiles($files);
    /**
     * 上传文件完成才可以提交
     * @returns {Wskeee.Uploader.isFinish}
     */
    function tijiao(){
        //uploader,isFinish 是否已经完成所有上传
        //uploader.hasError 是否有上传错误的文件
        //console.log(uploader.isFinish);
        return uploader.isFinish;
    } 
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>