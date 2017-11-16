<?php

use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $form ActiveForm */
?>

<div class="default-search">
    
    <?php $form = ActiveForm::begin([
        'id' => 'mcbs-search',
        'action' => ['attention'],
        'method' => 'get',
    ]); ?>
    
    <div class="col-xs-12 search"> 
        <div class="search-input">
            <?= Html::textInput('keyword', ArrayHelper::getValue($params, 'keyword'), [
                'class' => 'form-control search-text-input',
                'placeholder' => '输入名称查询课程'
            ]); ?>
        </div>
        <div class = "search-btn-bg">
            <?= Html::a('', 'javascript:;', ['id' => 'submit', 'class' => 'btn fa fa-search', 'style' => 'float: left;']); ?>
        </div>
    </div>
 
    <?php ActiveForm::end(); ?>

</div>

<?php

$js = 
<<<JS
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>
