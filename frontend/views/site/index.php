<?php

use frontend\views\SiteAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/*has-title*/
$this->title = '课程中心工作平台';
?>
<div class="container site-index  site-list" style="padding-top: 15px;">
    <div class="jumbotron" style="padding:0;margin: 0;">
        <div class="carousel slide" id="carousel-451276">
            <ol class="carousel-indicators">
                <?php foreach ($banner as $key => $value){
                    if($key == 0)
                        echo '<li class="active" data-slide-to="'.$key.'" data-target="#carousel-451276"></li>';
                    else
                        echo '<li data-slide-to="'.$key.'" data-target="#carousel-451276"></li>';
                }?>
            </ol>
            <div class="carousel-inner">
                <?php foreach ($banner as $key => $value){
                    if($key == 0)
                        echo '<div class="item active">';
                    else
                        echo '<div class="item">';
                    $fileSuffix = pathinfo($value->path, PATHINFO_EXTENSION);
                    if(in_array(strtolower($fileSuffix),$video))
                        echo '<video src="'.$value->path.'" type="video/mp4" width="100%">您的浏览器不支持 video 标签。</video>';
                    else
                        echo '<img class="center-block" alt="" src="'.$value->path.'"  width = "100%"/>';
                    echo '</div>';
                }?>
            </div>
            <!--<a data-slide="prev" href="#carousel-451276" class="left carousel-control">‹</a>
            <a data-slide="next" href="#carousel-451276" class="right carousel-control">›</a>-->
        </div>
    </div>
    
    <div class="body-content">
        <div class="jumbotron" style="padding:0;margin: 0;">
            <div class="row" style="margin:25px 0 0 0; background-color:#ccc;">
             <?php foreach ($system as $value){
                 echo '<div class="col-lg-3 col-sm-6" style=" padding:0px;">';
                 echo Html::a(Html::img($value->module_image,[
                         'class' => 'center-block',
                         'width' => '272',
                         'height' => '166',
                         'style' => 'margin-bottom:15px;',
                         'alt' => $value->des,
                     ]), $value->isjump == 0  ? $value->module_link : 
                         (!\Yii::$app->user->isGuest ? 
                             $value->module_link.'?userId='.$user->id.'&userName='.$user->username.'&timeStamp='.(time()*1000).'&sign='.strtoupper(md5($user->id.$user->username.(time()*1000).'eeent888888rms999999')) : 
                             $value->module_link),
                         [
                             'target'=> $value->isjump == 0 ? '' : "_black",
                             'title' => $value->module_link != '#' ? $value->name : '即将上线',
                         ]);
                 echo '</div>';
             }?>
            </div>
        </div>
    </div>
</div>
<?php  
 $js =   
<<<JS
    $('.carousel').carousel({
        interval: 3000
    });
    var item = $('.carousel .item')[0];
    if(item.firstChild.localName == 'video'){
        item.firstChild.play();
        item.lastChild.onplaying = function(){
            $('.carousel').carousel('pause');
        }
        item.lastChild.onended = function(){
            $('.carousel').carousel({
                interval: 2000
            });
        }
    }
    $('#carousel-451276').on('slid.bs.carousel', function () {
       var item = $('.carousel .item');
        $(".item").each(function(i,item) {
            if(item.firstChild.localName == "video" && item.className == 'item active' ){
                item.firstChild.play();
                item.firstChild.onplaying = function(){
                    $('.carousel').carousel('pause');
                }
                item.firstChild.onended = function(){
                    $('.carousel').carousel({
                        interval: 2000
                    });
                }
            }
        });
    });
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 


<?php
    SiteAsset::register($this);
?>