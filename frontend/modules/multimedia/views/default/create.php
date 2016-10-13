<?php

use common\models\multimedia\MultimediaTask;
use frontend\modules\multimedia\MultimediaAsset;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model MultimediaTask */

$this->title = Yii::t('rcoa/multimedia', 'Create Multimedia Task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="title">
    <div class="container">
        <?= $this->title ?>
    </div>
</div>

<div class="container multimedia-manage-create has-title">

    <?= $this->render('_form', [
        'model' => $model,
        'team' => $team,
        'itemType' => $itemType,
        'item' => $item,
        'itemChild' => $itemChild,
        'course' => $course,
        'contentType' => $contentType,
    ]) ?>

</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['list', 
            'create_by' => Yii::$app->user->id, 
            'assignPerson' => Yii::$app->user->id], ['class' => 'btn btn-default']) 
        ?>
        <?= Html::a(
                $model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'),
                'javascript:;', 
                ['id'=>'submit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php
$js = 
<<<JS
    $('#submit').click(function()
    {
        $('#multimedia-test-form').submit();
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    MultimediaAsset::register($this);
?>

