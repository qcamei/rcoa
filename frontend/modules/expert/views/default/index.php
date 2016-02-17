<?php

use common\models\expert\Expert;
use common\models\expert\searchs\ExpertSearch;
use frontend\modules\expert\ExpertAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ExpertSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Experts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container expert-index" style="padding:0;">
    <?= Html::img(Yii::$app->request->hostInfo.'/filedata/expert/personalImage/u183.jpg', [
        'id' => 'img',
        'class' => 'col-sm-12 col-md-12 col-xs-12 ',
        'height' => '360',
        'style' => 'padding:1.5%;'
    ])?>
    <?php foreach ($modelType as $modelBtn):?>
    <a class="btn btn-default btn-lg dropdown-toggle" href="<?= Yii::$app->request->hostInfo?>/expert/default/type?id=<?=$modelBtn->id?>">
        <?= $modelBtn->name?>(
            <?php 
                echo Expert::find()
                        ->where(['type' => $modelBtn->id])
                        ->count();
            ?>)
    </a>
    <?php endforeach;?>
    <b style="float: right;">专家总数：<?php 
        echo Expert::find()
            ->count();
    ?></b>
</div>





<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-10 col-md-11 col-xs-9">
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
    ExpertAsset::register($this);
?>