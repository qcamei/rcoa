<?php

use yii\web\View;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

    <form id="form-assign-key" action="<?= Yii::$app->request->hostInfo?>/expert/default/searchs" method="get">
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
         
   $('#submit').click(function(){
        if($('#form-assign-key input[name="key"]').val() == '')
           alert("请输入关键字");
        else
           $('#form-assign-key').submit();
   });
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 