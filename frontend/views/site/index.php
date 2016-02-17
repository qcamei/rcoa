<?php

use frontend\views\SiteAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = 'My Yii Application';
?>
<div class="container site-index bookdetail-list">
    <div class="jumbotron">
        <div class="carousel slide" id="carousel-451276">
            <ol class="carousel-indicators">
                    <li class="active" data-slide-to="0" data-target="#carousel-451276">
                    </li>
                    <li data-slide-to="1" data-target="#carousel-451276">
                    </li>
                    <li data-slide-to="2" data-target="#carousel-451276">
                    </li>
            </ol>
            <div class="carousel-inner">
                <div class="item active">
                    <img alt="" src="<?=Yii::$app->request->hostInfo?>/filedata/avatars/1.jpg" />
                    <div class="carousel-caption">
                            <h4>棒球</h4>
                            <p class="course-name">
                                棒球运动是一种以棒打球为主要特点，集体性、对抗性很强的球类运动项目，在美国、日本尤为盛行。
                            </p>
                    </div>
                </div>
                <div class="item">
                    <img alt="" src="<?=Yii::$app->request->hostInfo?>/filedata/avatars/2.jpg" />
                    <div class="carousel-caption">
                            <h4>
                                    冲浪
                            </h4>
                            <p class="course-name">
                                    冲浪是以海浪为动力，利用自身的高超技巧和平衡能力，搏击海浪的一项运动。运动员站立在冲浪板上，或利用腹板、跪板、充气的橡皮垫、划艇、皮艇等驾驭海浪的一项水上运动。
                            </p>
                    </div>
               </div>
                <div class="item">
                    <video id="video1" src="<?=Yii::$app->request->hostInfo?>/filedata/movies/jgpearslogo.mp4">
                        您的浏览器不支持 video 标签。
                    </video>
                </div>
            </div>
            <a data-slide="prev" href="#carousel-451276" class="left carousel-control">‹</a> 
            <a data-slide="next" href="#carousel-451276" class="right carousel-control">›</a>
        </div>
    </div>

    <div class="body-content">
        <div class="row">
            <?php foreach ($model as $module):?>
            <div class="col-sm-4">
                <a href="<?= Yii::$app->request->hostInfo.$module->module_link ?>"><p><?= Html::img(Yii::$app->request->hostInfo.$module->module_image,[
                    'class' => 'center-block',
                    'width' => '224',
                    'height' => '124',
                    'alt' => $module->des,
                ])?></p></a>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>

<?php  
 $js =   
<<<JS
    var width = $(window).width(); //浏览器当前窗口可视区域宽度
    if(width < 768){
        $("#video1").attr("controls","controls");
        var myVid = document.getElementById("video1");
        $('.carousel').carousel({
            interval: 2000
        });
        myVid.onplaying = function(){
            $('.carousel').carousel('pause');
        } 
        myVid.onended = function(){
            $('.carousel').carousel({
                interval: 2000
            });
        }
    }else{
        $('.carousel').carousel({
            interval: 2000
        });
        $('#carousel-451276').on('slid.bs.carousel', function () {
            var myVid = document.getElementById("video1");
            var active = $(".carousel-inner .item").eq(2).attr("class");
            if(active == "item active"){
                myVid.play();
                myVid.onplaying = function(){
                    $('.carousel').carousel('pause');
                }
                myVid.onended = function(){
                    $('.carousel').carousel({
                        interval: 2000
                    });
                }
            }
        });
    }
         
    
      
  
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 


<?php
    SiteAsset::register($this);
?>