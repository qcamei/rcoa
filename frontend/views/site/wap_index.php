<?php

use frontend\views\WapSiteAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/*has-title*/
$this->title = '工匠';
?>

<div class="site-home">
    <div class="site-home-logo">
        <?= Html::img(['/filedata/site/image/wap_site_logo.png'])?>
        <p><span class="site-home-title">课程建设工作平台</span></p>
    </div>
    <div class="container site-home-jumbotron">
        <div class="col-xs-12 site-home-circlebox line-box">  
            <div class="site-home-introduction" style="height: auto;">  
                <div class="line"></div>
            </div>  
        </div>
        <div class="col-xs-12 site-home-circlebox">
            <div class="site-home-introduction">
                <a href="/demand/default">
                    <div class="site-home-circle img-circle">
                        <span class="ciricle-num" id="count-number" ><span class="timer" data-to="<?= $total; ?>" data-speed="550">0</span><span class="num-words">个</span></span>
                        <span class="icon">+</span>
                    </div>
                </a>
                <div class="site-home-words">
                    <span class="site-home-words-en">Demand</span><br/><span class="site-home-words-ch">需求</span>
                    <i class="new-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-xs-12 site-home-circlebox line-box">  
            <div class="site-home-introduction" style="height: auto;">  
                <div class="line"></div>
            </div>  
        </div>
        
        <div class="col-xs-12 site-home-circlebox">
            <div class="site-home-introduction">
                <a href="/teamwork/default">
                    <div class="site-home-circle img-circle">
                        <span class="ciricle-num" id="count-number"><span class="timer" data-to="<?= $teamwork; ?>" data-speed="550">0</span><span class="num-words">个</span></span>
                        <span class="icon">+</span>
                    </div>
                </a>
                <div class="site-home-words">
                    <span class="site-home-words-en">Development</span><br/><span class="site-home-words-ch">开发</span>
                </div>
            </div>    
        </div>
        <div class="col-xs-12 site-home-circlebox line-box">  
            <div class="site-home-introduction" style="height: auto;">  
                <div class="line"></div>
            </div>  
        </div>
        
        <div class="col-xs-12 site-home-circlebox">
            <div class="site-home-introduction">
                <a href="/worksystem/default" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    <div class="site-home-circle img-circle">
                        <?= Html::img(['/filedata/site/system/development.png'], ['width' => '50']) ?>
                        <!--<span class="ciricle-num"><span class="timer" data-to="0" data-speed="550">0</span><span class="num-words">条</span></span>
                        <span class="icon">+</span>-->
                    </div>
                </a>
                <div class="site-home-words">
                    <span class="site-home-words-en">Task</span><br/><span class="site-home-words-ch">任务</span>
                </div>
            </div>
        </div>
<!--        <div class="col-xs-12 site-home-circlebox collapse" id="collapseExample">
            <div class="col-xs-12 site-home-circlebox line-box">  
                <div class="site-home-introduction" style="height: auto;">  
                    <div class="line subset-line"></div>
                </div>  
            </div>
            <div class="site-home-introduction" style="height: 50px;">
                <a href="/teamwork/default" style="padding: 0 25px">
                    <div class="subset-circle img-circle">进度</div>
                </a>
            </div>
            <div class="col-xs-12 site-home-circlebox line-box">  
                <div class="site-home-introduction" style="height: auto;">  
                    <div class="line subset-line"></div>
                </div>  
            </div>
            <div class="site-home-introduction" style="height: 50px;">
                <a href="/shoot/bookdetail" style="padding: 0 25px">
                    <div class="subset-circle img-circle">预约</div>
                </a>
            </div>
            <div class="col-xs-12 site-home-circlebox line-box">  
                <div class="site-home-introduction" style="height: auto;">  
                    <div class="line subset-line"></div>
                </div>  
            </div>
            <div class="site-home-introduction" style="height: 50px;">
                <a href="/multimedia/home" style="padding: 0 25px">
                    <div class="subset-circle img-circle">视频</div>
                </a>
            </div>
        </div>-->
        <div class="col-xs-12 site-home-circlebox line-box">  
            <div class="site-home-introduction" style="height: auto;">  
                <div class="line"></div>
            </div>  
        </div>
        
        <div class="col-xs-12 site-home-circlebox">
            <div class="site-home-introduction">
                <a href="/shoot/bookdetail">
                    <div class="site-home-circle img-circle">
                        <span class="ciricle-num" id="count-number"><span class="timer" data-to="3" data-speed="550">0</span><span class="num-words">场</span></span>
                        <span class="icon">+</span>
                    </div>
                </a>
                <div class="site-home-words">
                    <span class="site-home-words-en">Locations</span><br/><span class="site-home-words-ch">场地</span>
                </div>
            </div>    
        </div>
        <div class="col-xs-12 site-home-circlebox line-box">  
            <div class="site-home-introduction" style="height: auto;">  
                <div class="line"></div>
            </div>  
        </div>
        
    </div>
</div>

<?php
$hostInfo = Yii::$app->urlManager->hostInfo;
 $js =   
<<<JS
    if($undertakeCount > 0)
        $('.new-icon').animate({top: '-10px;',opacity:1}, 500, 'linear');
         
    $('.timer').each(count);  
   

JS;
   $this->registerJs($js,  View::POS_READY); 
?> 

<?php
    WapSiteAsset::register($this);
?>