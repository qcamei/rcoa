<?php

use common\models\scene\SceneBook;
use frontend\modules\scene\assets\SceneAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model SceneBook */

?>

<div class="col-xs-12 frame">
    <div class="col-xs-12 frame-title">
        <i class="icon fa fa-commenting"></i>
        <span><?= Yii::t('app', 'Message')?>（<span id="number"><?= $msgNum ?></span>）</span>
    </div>
    <div class="col-xs-12 frame-table msg">
        
        <div id="msg-list" class="msglist">
            <?= $this->render('msg_index', ['dataProvider' => $dataProvider, 'msgNum' => $msgNum]) ?>
        </div>
        <div class="msgform">
            <div class="col-lg-11 col-md-11 col-xs-10 msginput">

                <?php $form = ActiveForm::begin([
                    'options'=>[
                        'id' => 'form-msg',
                        'class'=>'form-horizontal',
                        'method' => 'post',
                    ],
                    'action'=>['create-msg', 'book_id' => $model->id]
                ]); ?>

                <?= Html::textarea('content',null,['placeholder' => '请输入你想说的话...']);  ?>

                <?php ActiveForm::end(); ?>

            </div>
            <div class="col-lg-1 col-md-1 col-xs-2 msgbtn">
                <?= Html::a(Yii::t('app', 'Message'), 'javascript:;', ['id'=>'submitsave', 'class'=>'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>

<?php 

$js = 
<<<JS
        
    //提交表单
    $("#submitsave").click(function(){
        //$('#form-msg').submit();return;
        var number = $("#number").text();
        $.post("/scene/scene-book/create-msg?book_id={$model->id}",$('#form-msg').serialize(),function(data){
            if(data['code'] == '200'){
                $("#number").text(parseInt(number) + parseInt(data['num']));
                $("#msg-list").load("/scene/scene-book/msg-index?book_id={$model->id}"); 
                $("#form-msg textarea").val("");
            }
        });
    });
   
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>