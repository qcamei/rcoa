<?php

use common\widgets\webuploader\WebUploaderAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model common\models\Uploadfile */

$this->title = Yii::t('app', '{Upload}{File}', [
            'Upload' => Yii::t('app', 'Upload'),
            'File' => Yii::t('app', 'File'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{File}{List}{Administration}', [
        'File' => Yii::t('app', 'File'),
        'List' => Yii::t('app', 'List'),
        'Administration' => Yii::t('app', 'Administration'),
    ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uploadfile-create">
    <div id="uploader">
        <?php ActiveForm::begin() ?>
        <div class="col-xs-12 col-sm-1" style="text-align: right; margin-top: 15px; padding-right: 0px">选择文件：</div>
        <div id="uploader-container" class="col-xs-12 col-sm-11">
        </div>
        <div class="col-sm-12" style="text-align: right;">
            <?= Html::submitButton('提交', ['class' => 'btn btn-success', 'onclick' => 'return tijiao();']) ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>
    <?php
    //获取flash上传组件路径
    $swfpath = $this->assetManager->getPublishedUrl(WebUploaderAsset::register($this)->sourcePath);
    ?>
    <script type='text/javascript'>
        var uploader;
        window.onload = function () {
            require(['euploader'], function (euploader) {
                var config = {
                    name: 'files',
                    swf: '<?= $swfpath ?>' + '/Uploader.swf',
                    // 文件接收服务端。
                    server: '/webuploader/default/upload',
                    //检查文件是否存在
                    checkFile: '/webuploader/default/check-file',
                    //分片合并
                    mergeChunks: '/webuploader/default/merge-chunks',
                    // 选择文件的按钮。可选。
                    // 上传容器
                    container: '#uploader-container',
                    formData: {
                        _csrf: "<?= Yii::$app->request->csrfToken ?>",
                        //指定文件上传到的应用
                        dir_path: 'admin',
                        app_id: '<?= Yii::$app->id ?>',
                        //同时创建缩略图
                        makeThumb: 0
                    }
                };
                uploader = new euploader.Uploader(config, euploader.FilelistView);
            });
        };

        /**
         * 上传文件完成才可以提交
         * @returns {Wskeee.Uploader.isFinish}
         */
        function tijiao() {
            return uploader.isFinish();
        }
    </script>
</div>
