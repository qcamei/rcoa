<?php

use frontend\views\SiteAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$this->title = 'My Yii Application';
?>
<div class="container site-index bookdetail-list">
  <div class="jumbotron">
        <?= Html::img('filedata/system/banner.jpg',[
                'class' => 'center-block',
                'width' => '100%',
                'style' => 'margin-top:15px;',
            ])
        ?>
    </div>
    <div class="body-content">
        <div class="jumbotron">
           <div style="background-color:#ccc;margin-top:25px;">
                <div class="row">
                <?php foreach ($model as $module){
                    echo '<div class="col-sm-3">';
                    echo Html::a(Html::img($module->module_image,[
                            'class' => 'center-block',
                            'width' => '272',
                            'height' => '166',
                            'alt' => $module->des,
                        ]), $module->isjump == 0  ? $module->module_link : 
                            (!\Yii::$app->user->isGuest ? 
                                $module->module_link.'?userId='.$user->id.'&userName='.$user->username.'&timeStamp='.(time()*1000).'&sign='.strtoupper(md5($user->id.$user->username.(time()*1000).'eeent888888rms999999')) : 
                                $module->module_link),
                            $module->isjump == 0 ? '': ['target'=>"_black"]);

                    echo '</div>';
                }?>
               </div>
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
    $this->registerJs($js,  View::POS_READY); 
?> 


<?php
    SiteAsset::register($this);
?>