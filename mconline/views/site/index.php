<?php

use mconline\assets\AppAsset;
use mconline\assets\SiteAssets;
use yii\web\View;

/* @var $this View */

$this->title = '在线制作课程平台';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-6">
                <a href="../mcbs/default/">
                    <div class="mcbs">
                        <p>板书课堂</p>
                    </div>
                </a>
            </div>
            <div class="col-lg-6">
                <a href="javasrip:;">
                    <div class="mcqj">
                        <p>情景课堂</p>
                    </div>
                </a>
            </div>
        </div>
        
    </div>
</div>

<?php
    AppAsset::register($this);
    SiteAssets::register($this);
?>