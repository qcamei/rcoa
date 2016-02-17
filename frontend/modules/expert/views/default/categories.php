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
            <?= Html::a('返回', '', ['class' => 'btn btn-default', 'onclick'=>'history.go(-1)']) ?>
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