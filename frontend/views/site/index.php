<?php

use frontend\assets\NetbuttonAssets;
use frontend\views\SiteAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/*has-title*/
$this->title = '课程中心工作平台';
?>

<div class="site-home">
    <div class="container">
        <div class="site-home-logo">
            <?= Html::img(['/filedata/site/image/site_logo.png'])?>
        </div>
        
        <div class="site-home-introduction">
            <span>课程建设分散式众包平台</span>
        </div>
        
        <div class="site-home-jumbotron">
            
            <div class="col-lg-3 site-home-circlebox">
                <a href="/demand/default">
                    <div class="site-home-circle img-circle">
                        <?= Html::img(['/filedata/site/system/task.png']) ?>
                        <div class="circle-bg img-circle">
                            <span class="timer ciricle-num" id="count-number" data-to="<?= $total; ?>" data-speed="550">0</span>
                            <span class="new-icon"></span>
                            <span class="num-words">个</span>
                        </div>
                    </div>
                </a>
                <div class="site-home-words">
                    <span class="site-home-words-ch">任务</span><br/><span class="site-home-words-en">Task</span>
                </div>
            </div>

            <div  class="col-lg-3 netbutton">
                
            </div>

            <div class="col-lg-3 site-home-circlebox">
                <a href="/expert/default">
                    <div class="site-home-circle img-circle">
                        <?= Html::img(['/filedata/site/system/teachers.png']) ?>
                        <div class="circle-bg img-circle">
                            <span class="timer ciricle-num" id="count-number" data-to="<?= $expert; ?>" data-speed="550">0</span>
                            <span class="icon">+</span>
                            <span class="num-words">名</span>
                        </div>
                    </div>
                </a>
                <div class="site-home-words">
                    <span class="site-home-words-ch">师资</span><br/><span class="site-home-words-en">Teachers</span>
                </div>
            </div>

            <div class="col-lg-3 site-home-circlebox">
                <a href="#">
                    <div class="site-home-circle img-circle">
                        <?= Html::img(['/filedata/site/system/locations.png']) ?>
                        <div class="circle-bg img-circle">
                            <span class="timer ciricle-num" id="count-number" data-to="228" data-speed="550">0</span>
                            <span class="icon"></span>
                            <span class="num-words">场</span>
                        </div>
                    </div>
                </a>
                <div class="site-home-words">
                    <span class="site-home-words-ch">场地</span><br/><span class="site-home-words-en">Locations</span>
                </div>
            </div>
                
        </div>
        
    </div>
    
<!--<div id="richbutton" class="col-lg-3 col-md-3 col-sm-6">您的浏览器不支持html5！请更换为chrome或者ie9以上浏览器！</div>-->
           
</div>

<?php  
 $js =   
<<<JS
    //开发按钮     
    var netbutton;
         
   /** 设置.site-home 大小*/
   size();
   $(window).resize(function(){
        size();
    });
    function size(){
        var height = $(document.body).height() - 100;
        if(height < 820)
            height = 820;
        $(".site-home").css({width:'100%',height:height, display:"block"});
    }
     
    /** 图标出场动画 */
    $('.site-home-circlebox').each(function(index, obj){
        setTimeout(function(){
            $(obj).animate({top:'0px', opacity:1}, 500, 'easeOutBack');
        }, 300 + index * 100)
    });
    $('.netbutton').each(function(index, obj){
        setTimeout(function(){
            $(obj).animate({top:'0px', opacity:1}, 500, 'easeOutBack');
        }, 300 + index * 100)
    });
    
         
    /** 鼠标经过跳动 */
    function init(){
        $('.site-home-circlebox').each(function(index, elem){
            $(elem).mouseover(function(){
                ShakeObj.get($(elem).find('.site-home-circle')[0],{styleName:'top', rang: 8,interactive:false}).start();
                ShakeObj.get($(elem).children('.site-home-words')[0],{styleName:'bottom', rang: 2, interactive:false}).start();
                $(elem).children('.site-home-words').addClass('color-replace');
            });
            $(elem).mouseout(function(){
                $(elem).children('.site-home-words').removeClass('color-replace');
            });
        });
        
        //初始
        netbutton = new Wskeee.ccoa.NetButton({
			path:'filedata/site/netbutton/',
			container:'.netbutton',
			onSelected:onSelected
		});
    }
    /**
     * 开发子按钮按下回调
     **/
    function onSelected(data){
        console.log(data);
    }     
     
    window.onload = init; 
    /** 鼠标经过换图标背景颜色 */      
    $('.site-home-circle').each(function(index, obj){
        $(obj).hover(function(){
            $(obj).children('.circle-bg').stop()
            $(obj).children('img').stop().fadeTo(200, 0, 'linear', function(){
                $(this).next('.circle-bg').stop().fadeTo(200, 1, 'linear', function(){
                    $(this).children('.timer').each(count);  // 启动所有定时器
                });
            });
        }, function(){
            $(obj).children('img').stop()
            $(obj).children('.circle-bg').stop().fadeTo(200, 0, 'linear', function(){
                $(obj).children('img').stop().fadeTo(200, 1);
                $(this).children('.timer').each(stop);  // 停止所有定时器
                $(this).children('.timer').html(0);
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