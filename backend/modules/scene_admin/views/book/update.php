<?php

use common\models\scene\SceneBook;
use frontend\modules\scene\assets\SceneAsset;
use yii\web\View;

/* @var $this View */
/* @var $model SceneBook */

$this->title = Yii::t('app', '{Bespeak}{List}：', [
            'Bespeak' => Yii::t('app', 'Bespeak'),
            'List' => Yii::t('app', 'List'),
        ]) . Yii::t('app', '{Update}{Bespeak}', [
            'Update' => Yii::t('app', 'Update'),
            'Bespeak' => Yii::t('app', 'Bespeak'),
        ]);
$bespeakName = "【{$model->sceneSite->name}】{$model->date} " .
        SceneBook::$timeIndexMap[$model->time_index] . "：{$model->course->name}";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Bespeak}{List}', [
        'Bespeak' => Yii::t('app', 'Bespeak'),
        'List' => Yii::t('app', 'List'),
    ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $bespeakName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="scene-book-update">

    <h1><?php //Html::encode($this->title)  ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'siteName' => $siteName,
        'business' => $business,
        'levels' => $levels,
        'professions' => $professions,
        'courses' => $courses,
        'teachers' => $teachers,
        'contentTypeMap' => $contentTypeMap,
        'createSceneBookUser' => $createSceneBookUser,
        'existSceneBookUser' => $existSceneBookUser,
    ])
    ?>

</div>
<?php
    SceneAsset::register($this);
?>