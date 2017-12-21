<?php

use frontend\views\WapSiteAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/*has-title*/
$this->title = '课程建设工作平台';
?>

<div class="site-index">
    <div class="course">
        <p><span class="CHS"><?= Html::encode($this->title) ?></span></p>
        <span class="EN">The Platform Of Curriculum Construction</span>
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
                        <?= Html::img(['/filedata/site/image/icon_1-3.png'], ['width' => '50']) ?>
                        <!--<span class="ciricle-num"><span class="timer" data-to="0" data-speed="550">0</span><span class="num-words">条</span></span>
                        <span class="icon">+</span>-->
                    </div>
                </a>
                <div class="site-home-words">
                    <span class="site-home-words-en">Task</span><br/><span class="site-home-words-ch">任务</span>
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