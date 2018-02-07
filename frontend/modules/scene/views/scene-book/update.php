<?php

use common\models\scene\SceneBook;
use frontend\modules\scene\assets\SceneAsset;
use yii\web\View;

/* @var $this View */
/* @var $model SceneBook */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Scene Book',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Scene Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?= $this->render('/layouts/_title', [
    'title' => Yii::t('app', 'Update')."：【{$model->sceneSite->name}】{$model->date} ".SceneBook::$timeIndexMap[$model->time_index]
]) ?>

<div class="container scene-book-update has-title scene">

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

<?= $this->render('/layouts/_form_nav', ['model' => $model, 'params' => ['view', 'id' => $model->id]]) ?>

<?php

$js = <<<JS
    
    $('#submit').click(function(){
        var str = selectLength();
        if(str.length > 0){
            $("#scene-book-form").submit();
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
    $this->registerJs($js, View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>