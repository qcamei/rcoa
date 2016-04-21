<?php

use common\models\resource\Resource;
use frontend\modules\resource\ResourceAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/resource', 'Resources');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container resource-index" style="padding:0;">
    <?= Html::img(['/filedata/system/u236_b.png'], [
        'id' => 'img',
        'class' => 'col-sm-12 col-md-12 col-xs-12 ',
        'height' => '360',

    ])?>
    <div class="jumbotron" style="padding:0;margin: 0;">
    <div class="row">
    <?php foreach ($model as $value){
        echo '<div class="col-lg-3 col-sm-6" style=" padding:0px;">';
        echo Html::a('<div class="resource-type-relative">'.
                Html::img([$value->image],['width' => '210', 'height' => '118']).
                '<div class="resource-type-absolute">'.
                '<span class="resource-type-text">'.$value->name.'</span><span class="resource-type-text-right">'.
                    Resource::find()
                    ->where(['type' => $value->id])
                    ->count().'个</span></div></div>',
                ['type', 'id' => $value->id], ['class' => 'resource-index-a']);
        echo '</div>';
    }?>
    </div>
    </div>
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-10 col-md-11 col-xs-9">
                <form id="form-assign-key" action="<?= Yii::$app->request->hostInfo?>/resource/default/searchs" method="get">
                    <input type="text" name="key"  class="form-control text searchtext" placeholder="请输入关键字..."/>
                </form>
            </div>
            <?= Html::a(Yii::t('rcoa', 'Search'), 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
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