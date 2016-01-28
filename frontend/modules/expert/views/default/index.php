<?php

use common\models\expert\Expert;
use common\models\expert\searchs\ExpertSearch;
use frontend\modules\expert\ExpertAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $searchModel ExpertSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Experts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container expert-index" style="padding:0;">
    <?= Html::img('http://rcoa.gzedu.net/filedata/expert/personalImage/u183.jpg', [
        'id' => 'img',
        'class' => 'col-sm-12 col-md-12 col-xs-12 ',
        'height' => '360',
        'style' => 'padding:1.5%;'
    ])?>
    <?php foreach ($modelType as $modelBtn):?>
    <a class="btn btn-default btn-lg dropdown-toggle" href="http://rcoa.gzedu.net/expert/default/type?id=<?=$modelBtn->id?>">
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
                <form id="form-assign-key" action="http://rcoa.gzedu.net/expert/default/categories" method="get">
                    <div id="radio">
                        <input type="radio" name="fieldName" value="all" checked/><label>全部</label>
                        <input type="radio" name="fieldName" value="job_title"/><label>头衔</label>
                        <input type="radio" name="fieldName" value="job_name"/><label>职称</label>
                        <input type="radio" name="fieldName" value="nickname"/><label>专家名称</label>
                        <input type="radio" name="fieldName" value="name"/><label>专家类型</label>
                        <input type="radio" name="fieldName" value="employer"/><label>单位信息</label>
                        <input type="radio" name="fieldName" value="attainment"/><label>主要成就</label>
                    </div>
                    <input type="text" name="key" class="form-control" placeholder="请输入关键字...">
                </form>
            </div>
            <?= Html::a('搜索', 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
        </div>
    </div>
</div>

<?php  
 $js =   
<<<JS
   /** 单击显示搜索字段 */
   $('#form-assign-key input[name = "key"]').click(function(){
       $('#radio').css("display","block");
   });
    /** 失去焦点移出搜索字段 */
   /*$('#form-assign-key input[name = "key"]').blur(function(){
       $('#radio').css("display","none");
   });*/
   
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