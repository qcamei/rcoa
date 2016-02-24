<?php

use common\models\expert\searchs\ExpertSearch;
use frontend\modules\expert\ExpertAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model ExpertSearch */
/* @var $form ActiveForm */
$this->title = 'Search';
?>
<!-- title 样式 -->
<div class="title">
    <div class="container">
        <?php echo '<b>'. $categories  .'</b>　 所有结果'; ?>
    </div>
</div>

<div class="container expert-type bookdetail-list has-title">
    <?php 
        if(count($modelKey) == 0)
            echo '<h2>未找到有关【'. $categories .'】的数据！</h2>';
    ?>
    
    <?php foreach ($modelKey as $key): ?>
    <a href="<?= Yii::$app->request->hostInfo?>/expert/default/view?id=<?= $key->u_id; ?>"><div style="height: 74px; border:1px solid #CCC;">
        <div style="float: left; ">
        <?= Html::img(Yii::$app->request->hostInfo.$key->personal_image, [
            'class' => 'img-rounded',
            'style' => 'margin:5px',
            'width' => '60',
            'height' => '60',
        ])?>
        </div>
        <div>
            <span style="margin-top:0.5%; display: block;"><b><?= $key->user->nickname ?>(<?= $key->job_title ?>)</b></span>
            <p style="margin:0;"><span>职称：</span><?= $key->job_name ?></p>
            <p class="course-name" ><span>描述：</span><?= $key->attainment ?></p>
        </div>
    </div></a>
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
            <?= Html::a('返回', '', ['class' => 'btn btn-default', 'onclick'=>'history.go(-1)']) ?>
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
         
    /** 提交搜索 */     
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