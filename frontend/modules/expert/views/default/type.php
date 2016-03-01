<?php

use common\models\expert\Expert;
use frontend\modules\expert\ExpertAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Expert */
$this->title = 'ExpertsType';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- title 样式 -->
<div class="title">
    <div class="container">
        <?= $model->expertType->name; ?>
    </div>
</div>

<div class="container expert-type bookdetail-list has-title" id="expert-type">
    <?php foreach ($modelExpert as $expert): ?>
    <a href="<?= Yii::$app->request->hostInfo?>/expert/default/view?id=<?= $expert['u_id']; ?>" id="thelist">
        <div style="height: 74px; border:1px solid #CCC;">
            <div style="float: left; ">
                <?= Html::img(Yii::$app->request->hostInfo.$expert['personal_image'], [
                    'class' => 'img-rounded',
                    'style' => 'margin:5px',
                    'width' => '60',
                    'height' => '60',
                ])?>
            </div>
            <div>
                <span style="margin-top:0.5%; display: block;"><b><?= $expert['user']['nickname'] ?>(<?= $expert['job_title'] ?>)</b></span>
                <p class="course-name"  style="margin:0;"><span>职称：</span><?= $expert['job_name'] ?></p>
                <p class="course-name" ><span>描述：</span><?= $expert['attainment'] ?></p>
            </div>
        </div>
    </a>
    <?php endforeach;?>
</div>    


<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-9 col-md-10 col-xs-7">
                <form id="form-assign-key" action="<?= Yii::$app->request->hostInfo?>/expert/default/categories" method="get">
                    <ul class="dropdown clearfix" style="display:none;">
                        <li><input type="radio" id="all" name="fieldName" value="all" checked/><label for="all"><strong>全部</strong></label></li>
                        <li><input type="radio" id="job_title" name="fieldName" value="job_title"/><label for="job_title"><strong>头衔</strong></label></li>
                        <li><input type="radio" id="job_name" name="fieldName" value="job_name"/><label for="job_name"><strong>职称</strong></label></li>
                        <li><input type="radio" id="nickname" name="fieldName" value="nickname"/><label for="nickname"><strong>专家名称</strong></label></li>
                        <li><input type="radio" id="name" name="fieldName" value="name"/><label for="name"><strong>专家类型</strong></label></li>
                        <li><input type="radio" id="employer" name="fieldName" value="employer"/><label for="employer"><strong>单位信息</strong></label></li>
                        <li><input type="radio" id="attainment" name="fieldName" value="attainment"/><label for="attainment"><strong>主要成就</strong></label></li>
                    </ul>
                    <ul class="clearfix">
                        <li>
                            <input type="text" name="key" value="" id="keyword" class="form-control text searchtext" placeholder="请输入关键字..."/>
                            <span class="arrowDown"></span>
                        </li>
                    </ul>
                </form>
            </div>
            <?= Html::a('搜索', 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
            <?= Html::a('返回', ['index'], ['class' => 'btn btn-default',]) ?>
        </div>
    </div>
</div>

<?php  
 $js =   
<<<JS
  var ui = $('#form-assign-key');
		
    /** 对焦点上单击“显示”下拉列表中 **/
    ui.find('.searchtext').bind('focus click',function(){
        ui.find('.arrowDown').addClass('arrowUp').removeClass('arrowDown').andSelf().find('.dropdown').slideDown(50);
    });
   /** 鼠标离开隐藏下拉 **/
    ui.bind('mouseleave',function(){
        ui.find('.arrowUp').addClass('arrowDown').removeClass('arrowUp').andSelf().find('.dropdown').slideUp(50);
    });       
         
/** 下拉加载 */
$(document).ready(function(){
    $(window).scroll(function () {
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if (scrollTop + windowHeight == scrollHeight) {
            $("#expert-type a").last().html('<center style="margin-top:10px;"><b>加载中...<b/></center>');
            setTimeout(function () {
                $("#expert-type a").last().html("");
                typeAjax(page);
            }, 2000);
        }
    });
});   
/** 单击显示搜索字段 */
   $('#form-assign-key input[name = "key"]').click(function(){
       $('#radio').css("display","block");
   });
/** 提交搜索关键字 */
$('#submit').click(function(){
     if($('#form-assign-key input[name="key"]').val() == '')
        alert("请输入关键字");
     else
        $('#form-assign-key').submit();
});
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 


<script type="text/javascript">
    var page = 0;       //当前页数
    var showNum = 15;    //每页显示数量
    var isPost = false; 
    var pageCount = <?=$pageCount?>; //总页数
    var maxPage = pageCount/showNum; //最大页数
function typeAjax(pagenum){
    if(pagenum+1 > Math.ceil(maxPage)){
        $("#expert-type a").last().html('<center style="margin-top:10px;"><b>无数据...<b/></center>');
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
            
            //console.log("page:"+page); //在console页面打印数据 
            
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