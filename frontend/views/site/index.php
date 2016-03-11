<?php

use frontend\views\SiteAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/*has-title*/
$this->title = '课程中心工作平台';
?>
<div class="container site-index  site-list">
    <div class="jumbotron" style="padding:0;margin: 0;">
        <?= Html::img('filedata/system/banner.jpg',[
                'class' => 'center-block',
                'width' => '100%',
                'style' => 'margin-top:15px;',
            ])
        ?>
    </div>
    <div class="body-content">
        <div class="jumbotron"  style="padding:0;margin: 0;">
            <div class="row" style="margin:25px 0 0 0; background-color:#ccc;">
             <?php foreach ($system as $value){
                 echo '<div class="col-lg-3 col-sm-6" style=" padding:0px;">';
                 echo Html::a(Html::img($value->module_image,[
                         'class' => 'center-block',
                         'width' => '272',
                         'height' => '166',
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
                interval: 5000
            });
        }
    }else{
        $('.carousel').carousel({
            interval: 5000
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
    //$this->registerJs($js,  View::POS_READY); 
?> 


<?php
    SiteAsset::register($this);
?>