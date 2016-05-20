<?php

use common\models\expert\Expert;
use frontend\modules\expert\ExpertAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Expert */
$this->title = Yii::t('rcoa', 'Experts Type').' : '. $model->expertType->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- title 样式 -->
<div class="title">
    <div class="container">
        <?= $this->title?>
    </div>
</div>

<div class="container expert-type bookdetail-list has-title" id="expert-type">
    <?php foreach ($modelExpert as $expert){
        echo Html::a('<div class="expert"><div class="personal-image">'
                    .Html::img($expert['personal_image'], [
                        'class' => 'img-rounded',
                        'style' => 'margin:5px; border:1px solid #CCC',
                        'width' => '60',
                        'height' => '60',
                    ]). '</div>'
                    . '<div><span class="nickname"><b>'.$expert['user']['nickname'].'('.$expert['job_title'].')</b></span>'
                    . '<p class="course-name"><span>职称：</span>'.$expert['job_name'].'</p>'
                    . '<p class="course-name" ><span>描述：</span>'.$expert['attainment'].'</p></div></div>', 
            ['view','id'=> $expert['u_id']], ['id' => 'thelist']);
        }
        //echo '<center style="margin-top:10px;"><b></b></center>';
    ?>
</div>    


<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-9 col-md-10 col-xs-7">
                <?= $this->render('_form_search')?>
            </div>
            <?= Html::a(Yii::t('rcoa', 'Search'), 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
            <?= Html::a(Yii::t('rcoa', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php  
 $js =   
<<<JS
/** 下拉加载 */
$(document).ready(function(){
    $(window).scroll(function () {
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if (scrollTop + windowHeight == scrollHeight) {
            //$("center b").html('加载中...');
            setTimeout(function () {
                //$("center b").html('');
                typeAjax(page);
            }, 2000);
        }
    });
});   
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 


<script type="text/javascript">
    var page = 0;       //当前页数
    var showNum = 10;    //每页显示数量
    var isPost = false; 
    var pageCount = <?=$pageCount?>; //总页数
    var maxPage = pageCount/showNum; //最大页数
function typeAjax(pagenum){
    if(pagenum+1 > Math.ceil(maxPage)){
        //$("center b").html('无数据...');
        return;    // 当前页数是否大于最大页数
    }
    isPost = true;
    //var _url = location.href;
    $.ajax({
        url:'/expert/default/dropdown',
        data:{page:pagenum+1,showNum:showNum},
        type:"post",
        dataType:"json",
        async:false,
        success:function(data){
            isPost = false;
            /** 是否正常请求 */
            if(data["result"] != 0 || page == data["data"]["page"])
            {
                console.warn("请求失败...！");
                return;
            }
            page = Number(data["data"]["page"]); //把对象的值转换为数字
            showNum = Number(data["data"]["showNum"]);
            
            var strHtml = "";
            var modelExpert = data.data.modelExpert;
            for(var i in modelExpert){
                strHtml += '<a href="'+data.data.url+'/expert/default/view?id='+modelExpert[i].u_id+'">';
                strHtml += '<div style="height: 74px; border:1px solid #CCC;">';
                strHtml += '<div style="float: left; "><img src="'+modelExpert[i].personal_image+'" class="img-rounded" width="60" height="60" style="margin:5px"/></div>';
                strHtml += '<div>';
                strHtml += '<span style="margin-top:0.5%; display: block;"><b>'+modelExpert[i].user.nickname+'('+modelExpert[i].job_title+')</b></span>';
                strHtml += '<p class="course-name" style="margin:0;"><span>职称：</span>'+modelExpert[i].job_name+'</p>';
                strHtml += '<p class="course-name" ><span>描述：</span>'+modelExpert[i].attainment+'</p>';
                strHtml += '</div>';
                strHtml += '</div>';
                strHtml += "</a>";
            }
            $('#expert-type').append(strHtml);
        }
    });
}
</script>

<?php
    ExpertAsset::register($this);
?>