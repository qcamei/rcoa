<?php

use common\models\scene\SceneBook;
use frontend\modules\scene\assets\SceneAsset;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model SceneBook */

$this->title = Yii::t(null, '{create}{modelClass}', [
    'create' => Yii::t('app', 'Create'),
    'modelClass' => Yii::t('app', 'Scene Book'),
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Scene Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/layouts/_title', [
    'title' => Yii::t('app', 'Create')."：【{$model->sceneSite->name}】{$model->date} ".SceneBook::$timeIndexMap[$model->time_index]
]) ?>

<div class="container scene-book-create has-title scene">

    <?= $this->render('_form', [
        'model' => $model,
        'business' => $business,
        'levels' => $levels,
        'professions' => $professions,
        'courses' => $courses,
        'teachers' => $teachers,
        'contentTypeMap' => $contentTypeMap,
        'createSceneBookUser' => $createSceneBookUser,
        'existSceneBookUser' => $existSceneBookUser,
    ]) ?>

</div>


<?= $this->render('/layouts/_form_nav', ['model' => $model, 'params' => array_merge(['exit-create'], $filters)]) ?>

<?= $this->render('/layouts/_model') ?>

<?php

$html = str_replace(array("\r\n", "\r", "\n"),"",$this->renderFile('@frontend/modules/scene/views/scene-book/_form_day.php', ['model' => $model, 'dayExistBook' => $dayExistBook]));
$js = 
<<<JS
        
    //定时执行
//    setTimeout(function(){
//        //var url = "/scene/scene-book/exit-create?id=$model->id&site_id=$model->site_id&date=$model->date&time_index=$model->time_index&date_switch=$model->date_switch"
//        $.get("/scene/scene-book/exit-create?id=$model->id", function(data){
//            if(data['code'] == '200'){
//                window.history.go(-1);
//            }
//        });
//    }, 2*60*1000); 
//    window.clearTimeout(exit);    
    //点击创建弹出    
    $('#submit').click(function(){
        var str = selectLength();
        if(str.length > 0){
            $(".myModal").html("");
            $('.myModal').modal("show").html('$html');
        }else{
            $(".field-scenebookuser-user_id").addClass("has-error");
            $(".field-scenebookuser-user_id .help-block").html("接洽人不能为空。");
        }
    });
    
    //组装选中的接洽人人数
    function selectLength(){
        var select = document.getElementById("scenebookuser-user_id");
        var strLength = [];
        for(i=0;i<select.length;i++){
            if(select.options[i].selected){
                strLength.push(select[i].value);
            }
        }
        return strLength;
    }
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>