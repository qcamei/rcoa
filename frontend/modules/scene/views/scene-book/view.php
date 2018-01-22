<?php

use common\models\scene\SceneBook;
use frontend\modules\scene\assets\SceneAsset;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model SceneBook */

$this->title =  !empty($model->course_id) ? $model->course->name : null;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Scene Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/layouts/_title', [
    'title' => "【{$model->sceneSite->name}】{$model->date} ".SceneBook::$timeIndexMap[$model->time_index]. "：{$this->title}"
]) ?>

<div class="container scene-book-view has-title scene">

    <div class="col-xs-12 frame">
       <div class="col-xs-12 frame-title">
           <i class="icon fa fa-file-text-o"></i>
           <span><?= Yii::t('app', '预约详情') ?></span>
       </div>

       <?= $this->render('_form_detai', [
           'model' => $model,
           'sceneBookUser' => $sceneBookUser,
       ]) ?>

    </div>
    
    <?= $this->render('_form_msg', ['model' => $model, 'dataProvider' => $dataProvider, 'msgNum' => $msgNum]) ?>
    
    <?= $this->render('appraise', ['appraiseResult' => $appraiseResult]) ?>
    
    <div class="col-xs-12 frame">
        <div class="col-xs-12 frame-title">
            <i class="icon fa fa-history"></i>
            <span><?= Yii::t('app', 'Action Log') ?></span>
        </div>
        <div id="action-log" class="col-xs-12 frame-table course-make-actlog">
            <div style="width: 100%;min-height: 450px;"><center>加载中...</center></div>
        </div>
    </div>
    
</div>

<?= $this->render('_form_view', [
    'model' => $model, 
    'isRole' => $isRole,
    'appraiseResult' => $appraiseResult,
]) ?>

<?= $this->render('/layouts/_model', ['model' => $model]) ?>

<?php
$actlog = Url::to(['scene-book/log-index', 'book_id' => $model->id]);
$js = <<<JS
    //加载操作记录列表
    $("#action-log").load("$actlog"); 
    //显示模态框
    window.myModal = function(elem){
        $(".myModal").html("");
        $('.myModal').modal("show").load(elem.attr("href"));
    }
        
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>