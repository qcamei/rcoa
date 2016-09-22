<?php

use common\models\multimedia\MultimediaCheck;
use frontend\modules\multimedia\MultimediaAsset;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model MultimediaCheck */

$this->title = Yii::t('rcoa/multimedia', 'Create Multimedia Check');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="title">
    <div class="container">
        <?= $this->title ?>
    </div>
</div>


<div class="container multimedia-check-create has-title">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['default/view', 'id' => $task_id], ['class' => 'btn btn-default']) ?>
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
        $('#multimedia-check-form').submit();
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    MultimediaAsset::register($this);
?>