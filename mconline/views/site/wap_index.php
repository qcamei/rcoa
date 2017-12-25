<?php

use mconline\assets\WapSiteAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/*has-title*/
$this->title = '在线制作课程平台';
?>

<div class="site-index">
    <div class="mconline">
        <div class="platform" style='background-image: url("<?= WEB_ROOT ?>/filedata/site/image/wap_site_homebg.jpg");'>
            <p><span class="CHS"><?= Html::encode($this->title) ?></span></p>
            <span class="EN">Online Making Of Course Platform</span>
        </div>
        <div class="menu">
            <div class="container">
                <div class="col-sm-6 col-xs-12 modules">
                    <a href="/mcbs/default/index">
                        <div class="classroom">
                            <div class="icon">
                                <?= Html::img(WEB_ROOT.'/filedata/site/image/icon_2-1.png', ['width'=>'67px']) ?>
                            </div>
                            <div class="name">
                                <p><span class="CHS">板书课堂</span></p>
                                <span class="EN">Blackboard class</span>
                            </div>
                        </div>
                    </a>
                    <!-- 右竖线 -->
                    <div class="line-y hidden-xs"></div>
                </div>
                <div class="col-sm-6 col-xs-12 modules">
                    <a href="javascript:;">
                        <div class="classroom">
                            <div class="icon">
                                <?= Html::img(WEB_ROOT.'/filedata/site/image/icon_2-2.png', ['width'=>'67px;']) ?>
                            </div>
                            <div class="name">
                                <p><span class="CHS">情景课堂</span></p>
                                <span class="EN">Situational class</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

 $js =   
<<<JS
 

JS;
   $this->registerJs($js,  View::POS_READY); 
?> 

<?php
    WapSiteAsset::register($this);
?>