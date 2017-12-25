<?php

use common\modules\preview\assets\PreviewAssets;
use yii\web\View;

/* @var $this View */

?>
<div class="bgc">
    <div class="preview-body">
        <div class="title">
            文件预览：<?= $doc['name'] ?>
        </div>
        <div class="content">
            <iframe src="http://ow365.cn/?i=14580&furl=<?= Yii::$app->urlManager->hostInfo . '/' . $doc['path'] ?>"></iframe>
<!--            <iframe src="http://ow365.cn/?i=14580&furl=http://course.tutor.eecn.cn/Demo/doconline/demo1.docx"></iframe>-->
        </div>
    </div>
</div>
<?php
PreviewAssets::register($this);
