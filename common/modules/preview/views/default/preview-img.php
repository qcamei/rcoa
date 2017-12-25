<?php

use common\modules\preview\assets\PreviewAssets;
use yii\web\View;

/* @var $this View */
?>
<div class="bgc">
    <div class="preview-body">
        <div class="title">
            <?= $img['name'] ?>
        </div>
        <div class="content">
            <img src="<?= '/' . $img['path'] ?>"/>
        </div>
    </div>
</div>
<?php
PreviewAssets::register($this);