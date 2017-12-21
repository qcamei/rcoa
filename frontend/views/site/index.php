<?php

use frontend\assets\NetbuttonAssets;
use frontend\views\SiteAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/*has-title*/
$this->title = '课程建设工作平台';
?>

<div class="site-index">

    <div class="course">
        <div class="platform container">
            <div class="logo">
                <?= Html::img(['/filedata/site/image/logo.png'], ['width'=> '220px']) ?>
            </div> 
            <div class="name">
                <p><span class="CHS"><?= Html::encode($this->title) ?></span></p>
                <span class="EN">The Platform Of Curriculum Construction</span>
            </div> 
        </div>
        <div class="menu">
            <div class="container">
                <!--需求-->
                <div class="col-lg-3 modules">
                    <a href="/demand/default">
                        <div class="circle img-circle">
                            <?= Html::img(['/filedata/site/image/icon_1-1.png']) ?>
                            <div class="circle-bg img-circle">
                                <span class="number"><span class="timer" data-to="<?= $total; ?>" data-speed="550">0</span><span class="unit">个</span></span>
                                <span class="icon">+</span>
                            </div>
                        </div>
                    </a>
                    <div class="words">
                        <p><span class="CHS">需求</span></p>
                        <span class="EN">Demand</span>
                        <i class="new"></i>
                    </div>
                </div>
                <!--开发-->
                <div class="col-lg-3 modules">
                    <a href="/teamwork/default">
                        <div class="circle img-circle">
                            <?= Html::img(['/filedata/site/image/icon_1-2.png']) ?>
                            <div class="circle-bg img-circle">
                                <span class="number"><span class="timer" data-to="<?= $teamwork; ?>" data-speed="550">0</span><span class="unit">个</span></span>
                                <span class="icon">+</span>
                            </div>
                        </div>
                    </a>
                    <div class="words">
                        <p><span class="CHS">开发</span></p>
                        <span class="EN">Development</span>
                    </div>
                </div>
                <!--任务-->
                <div class="col-lg-3 netbutton">
                    <a href="javascript:;">
                        <div class="circle img-circle">
                            <?= Html::img(['/filedata/site/image/icon_1-3.png']) ?>
                            <div class="circle-bg img-circle">
                                <span class="number"><span class="timer" data-to="0" data-speed="550">0</span><span class="unit">个</span></span>
                                <span class="icon">+</span>
                            </div>
                        </div>
                    </a>
                    <div class="words">
                        <p><span class="CHS">任务</span></p>
                        <span class="EN">Task</span>
                    </div>
                </div>
                <!--场地-->
                <div class="col-lg-3 modules">
                    <a href="/shoot/bookdetail">
                        <div class="circle img-circle">
                            <?= Html::img(['/filedata/site/image/icon_1-4.png']) ?>
                            <div class="circle-bg img-circle">
                                <span class="number"><span class="timer" data-to="3" data-speed="550">0</span><span class="unit">场</span></span>
                                <span class="icon">+</span>
                            </div>
                        </div>
                    </a>
                    <div class="words">
                        <p><span class="CHS">场地</span></p>
                        <span class="EN">Locations</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php

$hostInfo = \Yii::$app->urlManager->hostInfo;

 $js =   
<<<JS
   
    //开发按钮     
    var netbutton;
    //new图标
    if($undertakeCount > 0)
        $('.words .new').animate({top: '-10px;',opacity: 1}, 500, 'linear');
      
    //图标出场动画
    $(".menu .container .modules").each(function(i, e){
        setTimeout(function(){
            $(e).animate({top: 0, opacity: 1}, 500, "easeOutBack");
        }, 300 + i * 100)
    });
    $(".menu .container .netbutton").each(function(i, e){
        setTimeout(function(){
            $(e).animate({top: 0, opacity: 1}, 500, "easeOutBack", function(){
                //初始画板
                netbutton = new Wskeee.ccoa.NetButton({
                    path:'filedata/site/netbutton/',
                    container:'.netbutton',
                    onSelected:onSelected
                });
            });
        }, 300 + i * 100)
    });         
    //鼠标经过跳动
    $('.menu .container .modules').each(function(i, e){
        $(e).hover(function(){
            ShakeObj.get($(e).find('.circle')[0],{styleName:'top', rang: 8,interactive:false}).start();
            ShakeObj.get($(e).children('.words')[0],{styleName:'bottom', rang: 2, interactive:false}).start();
            $(e).children(".words").addClass("replace");
        },function(){
            $(e).children(".words").removeClass("replace");
        });
    });
    //开发子按钮按下回调
    function onSelected(data){}     
    // 鼠标经过换图标背景颜色  
    $('.menu .container .modules .circle').each(function(i, e){
        $(e).hover(function(){
            $(e).children('.circle-bg').stop()
            $(e).children('img').stop().fadeTo(200, 0, 'linear', function(){
                $(this).next('.circle-bg').stop().fadeTo(200, 1, 'linear', function(){
                    $(this).children().children('.timer').each(count);  // 启动所有定时器
                });
            });
        }, function(){
            $(e).children('img').stop()
            $(e).children('.circle-bg').stop().fadeTo(200, 0, 'linear', function(){
                $(e).children('img').stop().fadeTo(200, 1);
                $(this).children().children('.timer').each(stop);  // 停止所有定时器
                $(this).children().children('.timer').html(0);
                $(this).children('.icon').fadeTo(200, 0);
            });
        });
    });  
   

JS;
   $this->registerJs($js,  View::POS_READY); 
?> 

<?php
    SiteAsset::register($this);
    NetbuttonAssets::register($this);
?>