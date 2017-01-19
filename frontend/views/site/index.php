<?php

use frontend\assets\RichbuttonAssets;
use frontend\views\SiteAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/*has-title*/
$this->title = '课程中心工作平台';
?>

<div class="homebg">
    <div class="banner"><?= Html::img(['/filedata/site/banner/banner.jpg'])?></div>
    <div class="container site-index">
        <div class="jumbotron">
            <div class="col-lg-3 col-md-3 col-sm-6" style="height:255px;">
                <div class="circle">
                    <a href="/demand/default">
                        <div class="img-circle">
                            <img src="/filedata/site/image/task.png" width="88px" height="96"/>
                        </div>
                    </a>
                    <div class="words">
                        <p style="margin-top: -10px;margin-bottom: 2px;"><span style="font-size:20px">任务</span></p>
                        <p style="margin-top: -12px;margin-bottom: 0px;"><span style="font-size:16px">Task</span></p>
                    </div>
                </div>
            </div>
            <div id="richbutton" class="col-lg-3 col-md-3 col-sm-6">您的浏览器不支持html5！请更换为chrome或者ie9以上浏览器！</div>
            <div class="col-lg-3 col-md-3 col-sm-6" style="height:255px;">
                <div class="circle">
                    <a href="/expert/default">
                        <div class="img-circle">
                            <img src="/filedata/site/image/teachers.png" width="88px" height="96"/>
                        </div>
                    </a>
                    <div class="words">
                        <p style="margin-top: -10px;margin-bottom: 2px;"><span style="font-size:20px">师资</span></p>
                        <p style="margin-top: -12px;margin-bottom: 0px;"><span style="font-size:16px">Teachers</span></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6" style="height:255px;">
                <div class="circle">
                    <a href="#">
                        <div class="img-circle">
                            <img src="/filedata/site/image/locations.png" width="88px" height="96"/>
                        </div>
                    </a>
                    <div class="words">
                        <p style="margin-top: -10px;margin-bottom: 2px;"><span style="font-size:20px">场地</span></p>
                        <p style="margin-top: -12px;margin-bottom: 0px;"><span style="font-size:16px">Locations</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php  
 $js =   
<<<JS
    var richbutton;

    function init(){
        richbutton = new Wskeee.ccoa.RichButton({
                path:'filedata/site/richbutton/',   //资源路径
                container:'#richbutton',            //按钮div id
                onSelected:onSelected               //选择回调
        });
    }

    function onSelected(index){
        if(index == 0){
            location.href = "/shoot/bookdetail";
        }else if(index == 1){
            location.href = "/multimedia/home";
        }else{
            location.href = "/teamwork/default"
        }
    }
         
    window.onload = init;
JS;
   $this->registerJs($js,  View::POS_READY); 
?> 


<?php
    SiteAsset::register($this);
    RichbuttonAssets::register($this);
?>