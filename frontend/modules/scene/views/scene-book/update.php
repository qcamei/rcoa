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
        $("#scene-book-form").submit();
    });    
        
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>