<?php

use common\modules\preview\assets\PreviewAssets;
use yii\web\View;

/* @var $this View */
?>
<div class="bgc">
    <div class="preview-body">
        <div class="title">
            错误提示！！！
        </div>
        <div class="content">
            <h2>该文件格式暂不支持预览</h2>
            <h2>请<a href="<?= Yii::$app->urlManager->hostInfo . '/mcbs/course-make/download' .
                '?activity_id=' . $other['activity_id'] . '&file_id=' . $other['file_id'] ?>">下载</a>后查看</h2>
        </div>
    </div>
</div>
<?php
PreviewAssets::register($this);
