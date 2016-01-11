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
    <?php foreach ($modelKey as $key): ?>
    <a href="http://rcoa.gzedu.net/expert/default/view?id=<?= $key['u_id']; ?>"><div style="height: 74px; border:1px solid #CCC;">
        <div style="float: left; ">
        <?= Html::img($key['personal_image'], [
            'class' => 'img-rounded',
            'style' => 'margin:5px',
            'width' => '60',
            'height' => '60',
        ])?>
        </div>
        <div>
            <span style="margin-top:0.5%; display: block;"><b><?= $key['user']['nickname'] ?>(<?= $key['job_title'] ?>)</b></span>
            <p style="margin:0;"><span>职称：</span><?= $key['job_name'] ?></p>
            <p class="course-name" ><span>描述：</span><?= $key['attainment'] ?></p>
        </div>
    </div></a>
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
            <?= Html::a('返回', '', ['class' => 'btn btn-default', 'onclick'=>'history.go(-1)']) ?>
        </div>
    </div>
</div>

<?php  
 $js =   
<<<JS
   $('#submit').click(function(){
       $('#form-assign-key').submit();
   });
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 

<?php
    ExpertAsset::register($this);
?>