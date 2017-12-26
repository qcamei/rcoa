<?php

use common\modules\preview\assets\PreviewAssets;
use yii\web\View;

/* @var $this View */
//$is = array(host => i);
$is = Yii::$app->params['ow365']['i'];
$url = Yii::$app->params['ow365']['url'];
$host = Yii::$app->urlManager->hostInfo;

?>
<div class="bgc">
    <div class="preview-body">
        <div class="title">
            文件预览：<?= $doc['name'] ?>
        </div>
        <div class="content">
            <iframe src="<?= $url . '?i='.$is[$host].'&furl='.$host . '/' . $doc['path'] ?>"></iframe>
            <!-- <iframe src="http://ow365.cn/?i=14580&furl=<?= Yii::$app->urlManager->hostInfo . '/' . $doc['path'] ?>"></iframe> -->
            <!-- <iframe src="http://ow365.cn/?i=14580&furl=http://course.tutor.eecn.cn/Demo/doconline/demo1.docx"></iframe> -->
        </div>
    </div>
</div>
<?php
PreviewAssets::register($this);
