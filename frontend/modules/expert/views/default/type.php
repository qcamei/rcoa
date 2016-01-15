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
    <a href="http://rcoa.gzedu.net/expert/default/view?id=<?= $expert['u_id']; ?>" id="thelist">
        <div style="height: 74px; border:1px solid #CCC;">
            <div style="float: left; ">
                <?= Html::img($expert['personal_image'], [
                    'class' => 'img-rounded',
                    'style' => 'margin:5px',
                    'width' => '60',
                    'height' => '60',
                ])?>
            </div>
            <div>
                <span style="margin-top:0.5%; display: block;"><b><?= $expert['user']['nickname'] ?><?= $expert['u_id']?>(<?= $expert['job_title'] ?>)</b></span>
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
                <?php $form = ActiveForm::begin(['action' => ['categories'],'method' => 'get', 'id' => 'form-assign-key']); ?>
                <?= Html::textInput('key', '', ['class' => 'form-control', 'placeholder' => '请输入关键字...',]) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <?= Html::a('搜索', 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
            <?= Html::a('返回', ['index'], ['class' => 'btn btn-default',]) ?>
        </div>
    </div>
</div>

<?php  
 $js =   
<<<JS
$(document).ready(function(){
    $(window).scroll(function () {
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if (scrollTop + windowHeight == scrollHeight) {
            setTimeout(function () {
                typeAjax(page);
            }, 1500);
        }
    });
   
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
    var pageNum = 15;    //每页显示数量
    var isPost = false; 
    var pageCount = <?=$pageCount?>; //总页数
    var maxPage = pageCount/pageNum; //最大页数
function typeAjax(pagenum){
    if(pagenum+1 > Math.ceil(maxPage))return;    // 当前页数是否大于最大页数
    isPost = true;
    var _url = location.href;
    $.ajax({
        url:_url,
        data:{page:pagenum+1,pageNum:pageNum},
        type:"post",
        dataType:"json",
        async:false,
        success:function(data){
            isPost = false;
            /** 是否正常请求 */
            if(data["result"] != 1 || page == data["data"]["page"])
            {
                console.warn("请求失败...！");
                return;
            }
            page = Number(data["data"]["page"]); //把对象的值转换为数字
            pageNum = Number(data["data"]["pageNum"]);
            
            //console.log("page:"+page); //在console页面打印数据 
            
            var strHtml = "";
            var modelExpert = data.data.modelExpert;
            for(var i in modelExpert){
                strHtml += '<a href="http://rcoa.gzedu.net/expert/default/view?id='+modelExpert[i].u_id+'">';
                strHtml += '<div style="height: 74px; border:1px solid #CCC;">';
                strHtml += '<div style="float: left; "><img src="'+modelExpert[i].personal_image+'" class="img-rounded" width="60" height="60" style="margin:5px"/></div>';
                strHtml += '<div>';
                strHtml += '<span style="margin-top:0.5%; display: block;"><b>'+modelExpert[i].user.nickname+modelExpert[i].u_id+'('+modelExpert[i].job_title+')</b></span>';
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