<?php

use mconline\assets\AppAsset;
use mconline\assets\SiteAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = '在线制作课程平台';
?>
<div class="site-index">

    <div class="mconline" style='background-image: url("/upload/site/images/site_homebg.jpg");'>
        <div class="platform container">
            <div class="logo">
                <?= Html::img('/upload/site/images/logo.png', ['width'=> '220px']) ?>
            </div> 
            <div class="name">
                <p><span class="CHS"><?= Html::encode($this->title) ?></span></p>
                <span class="EN">Online Making Of Course Platform</span>
            </div> 
        </div>
        <div class="menu">
            <div class="container">
                <div class="modules">
                    <a href="/mcbs/default/index">
                        <div class="classroom">
                            <div class="icon">
                                <?= Html::img('/upload/site/images/icon_2-1.png', ['width'=>'67px']) ?>
                            </div>
                            <div class="name">
                                <p><span class="CHS">板书课堂</span></p>
                                <span class="EN">Blackboard class</span>
                            </div>
                        </div>
                    </a>
                    <!-- 右竖线 -->
                    <div class="line-y"></div>
                </div>
                <div class="modules">
                    <a href="/mcqj/default/index">
                        <div class="classroom">
                            <div class="icon">
                                <?= Html::img('/upload/site/images/icon_2-2.png', ['width'=>'67px;']) ?>
                            </div>
                            <div class="name">
                                <p><span class="CHS">情景课堂</span></p>
                                <span class="EN">Situational class</span>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- 下横线  -->
                <div class="line-x">
                    <div class="line-left"></div>
                    <div class="line-right"></div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php
$js = 
<<<JS
    var timeOut;
    $(".menu .container .modules").each(function(){
       var demo = $(this);
       var outerWidth = demo.css("margin-left");
       $(this).children("a").hover(function(){
            clearTimeout(timeOut);
            var left = $(".line-left").position().left;
            if(left == 0){
                $(".line-x").css({left: (demo.position().left - $(this).width() / 2) + parseInt(outerWidth.replace("px","")) - 15});
            }else{
                $(".line-x").stop().animate({left: (demo.position().left - $(this).width() / 2) + parseInt(outerWidth.replace("px","")) - 15}, 400);
            }
            $(".line-left").stop().animate({left: "-110px"}, 200);
            $(".line-right").stop().animate({right: "-110px"}, 200);
       },function(){
            timeOut = setTimeout(function(){
                $(".line-left").stop().animate({left: 0}, 200);
                $(".line-right").stop().animate({right: 0}, 200);
            }, 200);
       });
       
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    AppAsset::register($this);
    SiteAssets::register($this);
?>