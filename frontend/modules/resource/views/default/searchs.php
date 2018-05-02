<?php

use common\models\resource\Resource;
use common\models\resource\ResourcePath;
use frontend\modules\resource\ResourceAsset;
use yii\helpers\Html;
use yii\web\View;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* @var $this View */
/* @var $model Resource */
$this->title = Yii::t('rcoa/resource', 'Resource Search').' : '. $categories;
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- title 样式 -->
<div class="title">
    <div class="container">
        <?= $this->title?>
    </div>
</div>

<div class="container resource-type bookdetail-list has-title" id="resource-type">
    <div class="row">
    <?php 
        if(count($modelKey) == 0)
            echo '<h2>未找到有关<span style="color:blue"> '. $categories .' </span>的资源！</h2>';
        
        foreach ($modelKey as $value){
            $img = ResourcePath::findOne(['r_id' => $value->id]);
            echo '<div class="col-md-3 col-sm-4 col-xs-6">';
            echo Html::a('<div class="resource-type-relative">'.
                Html::img([$img->path], ['class'=>'img-responsive center-block']).
                   '<div class="resource-type-absolute">'.
                   '<p class="resource-type-text">'.$value->name.'</p>'.
                   '</div></div>',
                   ['view', 'id'=>$value->id], ['class'=>'resource-type-a']);
            echo '</div>';
    }?>
    </div>
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-9 col-md-10 col-xs-6">
                 <form id="form-assign-key" action="<?= Yii::$app->request->hostInfo?>/resource/default/searchs" method="get">
                    <input type="text" name="key"  class="form-control text searchtext" placeholder="请输入关键字..."/>
                </form>
            </div>
            <?= Html::a(Yii::t('rcoa', 'Search'), 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
            <?= Html::a(Yii::t('rcoa', 'Back'), '', ['class' => 'btn btn-default','onclick'=>'history.go(-1)']) ?>
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
    ResourceAsset::register($this);
?>