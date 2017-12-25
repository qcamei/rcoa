<?php

use common\modules\preview\assets\PreviewAssets;
use yii\web\View;

/* @var $this View */
?>
<div class="bgc">
    <div class="preview-body">
        <div class="title">
            <?= $video['name'] ?>
        </div>
        <div class="content">
            <video controls="" autoplay="" name="media">
                <source src="<?=  '/' . $video['path'] ?>" type="video/mp4">
            </video>
        </div>
    </div>
</div>
<?php
PreviewAssets::register($this);
