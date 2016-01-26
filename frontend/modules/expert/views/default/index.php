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
                <?php $form = ActiveForm::begin(['action' => ['categories'],'method' => 'get', 'id' => 'form-assign-key']); ?>
                <?= Html::textInput('key', '', ['class' => 'form-control', 'placeholder' => '请输入关键字...',]) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <?= Html::a('搜索', 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
        </div>
    </div>
</div>


<?php  
 $js =   
<<<JS
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