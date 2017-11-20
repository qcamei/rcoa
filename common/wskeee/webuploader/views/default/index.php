<?php

use common\widgets\webuploader\WebUploaderAsset;
use wskeee\webuploader\models\Uploadfile;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="webuploader-default-index">
    <div id="uploader" class="container">
        <?php ActiveForm::begin() ?>
        <div class="col-xs-12 col-sm-2" style="text-align: right;">文件上传：</div>
        <div id="uploader-container" class="col-xs-12 col-sm-10">
        </div>
        <?= Html::submitButton('提交',['class' => 'btn btn-default']) ?>
        <?php ActiveForm::end() ?>
    </div>
    <?php
    $swfpath = $this->assetManager->getPublishedUrl(WebUploaderAsset::register($this)->sourcePath);
    $files = json_encode(Uploadfile::find()->asArray()->all());
    ?>
    <script type='text/javascript'>
        var uploader;
        window.onload = function () {
            uploader = new Wskeee.Uploader({
                // 文件接收服务端。
                server: '/webuploader/default/upload',
                //检查文件是否存在
                checkFile: '/webuploader/default/check-file',
                //分片合并
                mergeChunks: '/webuploader/default/merge-chunks',
                //flash上传组件
                swf: '<?= $swfpath ?>' + '/Uploader.swf',
                // 上传容器
                container: '#uploader-container',
                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: '#picker',
                // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
                resize: false,
                //分片
                chunked: true,
                //自动上传
                auto: false,
                //是否使用二进制上传
                sendAsBinary: true,
                formData: {
                    _csrf: "<?= Yii::$app->request->csrfToken ?>",
                    app_path: 'mcoline',
                }
            });
            uploader.addCompleteFiles(<?= $files ?>);
        }
    </script>
</div>
