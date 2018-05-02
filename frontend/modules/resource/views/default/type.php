<?php

use common\models\resource\Resource;
use common\models\resource\ResourcePath;
use frontend\modules\resource\ResourceAsset;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\View;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* @var $this View */
/* @var $model Resource */
$this->title = Yii::t('rcoa/resource', 'Resource Type').' : '. $model->resourceType->name.'('.Resource::find()
                    ->where(['type' => $model->id])
                    ->count().'个)';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- title 样式 -->
<div class="title">
    <div class="container">
        <?= $this->title?>
    </div>
</div>

<div class="container resource-type bookdetail-list has-title body-content" id="resource-type">
    <div class="row row-type">
    <?php foreach ($resource as $value){
        //$img = ResourcePath::findOne(['r_id' => $value->id]);
        echo '<div class="col-md-3 col-sm-4 col-xs-6">';
        echo Html::a('<div class="resource-type-relative">'.
             Html::img([$value->image], ['class'=>'img-responsive center-block']).
                '<div class="resource-type-absolute">'.
                '<p class="resource-type-text">'.$value->name.'</p>'.
                '</div></div>',
                ['view', 'id'=>$value->id], ['class'=>'resource-type-a','data-toggle'=>'modal', 'data-target'=>'#myModal']);
        echo '</div>';
    }?>
    </div>
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-9 col-md-10 col-xs-6">
                 <form id="form-assign-key" action="<?= Yii::$app->request->hostInfo?>/resource/default/searchs" method="get">
                    <input type="text" name="key"  class="form-control text searchtext" placeholder="请输入关键字..."/>
                </form>
            </div>
            <?= Html::a(Yii::t('rcoa', 'Search'), 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
            <?= Html::a(Yii::t('rcoa', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<div id="myModal" class="fade modal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 1280px;height: 720px;">
        <div class="modal-content">
            
        </div>
    </div>
</div>

<?php  
$js =   
<<<JS
   
    $('.resource-type-a').click(function(){
        var urlf = $(this).attr("href");
        $("#myModal").modal({remote:urlf});
        return false;
    });    
    window.onresize = function(){
        fix();
    }
    function fix(){
        var width = $('#myModal .modal-lg').width();
        var height = $('#myModal .modal-lg').height();
        var stageWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        var stageHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight; 
        var targetWidth = stageWidth,
            targetHeight = stageHeight;
        var scaleW = targetWidth/width;
        var scaleH = targetHeight/height;  
        var scale = Math.min(scaleW,scaleH);  
	width = width*scale;
	height = height*scale;
        $('#myModal .modal-lg').css('width',width+"px");
        $('#myModal .modal-lg').css('height',height+"px");
        $('#myModal .modal-lg').css('margin',"0px");
        $('#myModal .modal-lg').css('top',(stageHeight - height >> 1)+"px");
        $('#myModal .modal-lg').css('left',(stageWidth - width >> 1)+"px");
    }
    /** 从远端的数据源加载完数据之后触发该事件。 */
    $('#myModal').on('loaded.bs.modal', function () {
        $('.carousel').carousel('pause');
        $('#carousel-731952').on('slid.bs.carousel', function () {
             $(".item").each(function(i,item) {
                $(this).find("video").each(function(){
                    this.pause();
                });
             });
         });
        fix();
        //鼠标移上显示控制按钮
        var leftRightHideDelayID;
        $("#carousel-731952").hover(
            function () {
                $('#carousel-731952 .carousel-indicators').fadeIn();
                $('#carousel-731952 .left').fadeIn();
                $('#carousel-731952 .right').fadeIn();
                $('#carousel-731952 .display').fadeIn();
                clearTimeout(leftRightHideDelayID);
            },
            function () {
                //开定时器
                leftRightHideDelayID = setTimeout(function () {
                    $('#carousel-731952 .carousel-indicators').fadeOut();
                    $('#carousel-731952 .left').fadeOut();
                    $('#carousel-731952 .right').fadeOut();
                    $('#carousel-731952 .display').fadeOut();
                }, 1000);
            }
        );
        $("#myModal .modal-header .close").click(function(){
            window.location.reload();
        });
        $(".display").click(function(){
            var noShow =  $(".carousel-caption").css("display");
            if(noShow == 'none'){
                $(".carousel-caption").fadeIn();    //渐显效果
                $(this).attr("src","/css/imgs/u466.png");
            }else{
               $(".carousel-caption").fadeOut();
               $(this).attr("src","/css/imgs/u462.png");
            }
        });
    })
    /** 隐藏之后触发该事件*/
    $('#myModal').on('hidden.bs.modal', function () {
        var myVideo = document.getElementById("myVideo");
        if(myVideo != null)
            myVideo.pause();
        window.location.reload();
    });
    $('#submit').click(function(){
        if($('#form-assign-key input[name="key"]').val() == '')
            alert("请输入关键字");
        else
            $('#form-assign-key').submit();
    });
         
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 

<?php
    ResourceAsset::register($this);
?>