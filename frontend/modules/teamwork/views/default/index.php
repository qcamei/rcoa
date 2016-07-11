<?php

use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TwAsset;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ItemManage */
/* @var $dataProvider ActiveDataProvider */


$this->title = Yii::t('rcoa/teamwork', 'Item Manages');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container item-manage-index item-manage">
    
    <?php 
        echo Html::beginTag('div', ['class' => 'item-manage-head']);
            echo Html::beginTag('div', ['class' => 'item-manage-headline']).'课程中心'.Html::endTag('div');
            echo Html::beginTag('div', ['class' => 'lession-time']);
                echo '<p>已完成：<span>'.array_sum($completedLessionTimes).'</span>学时'.
                Html::a('', ['index', 'status' => ItemManage::STATUS_CARRY_OUT], ['class' => 'glyphicon glyphicon-eye-open ']).'</p>
                <p>&nbsp;&nbsp;&nbsp;在做：<span>'.array_sum($undoneLessionTimes).'</span>学时'.
                Html::a('', ['index', 'status' => ItemManage::STATUS_NORMAL], ['class' => 'glyphicon glyphicon-eye-open ']).'</p>'; 
            echo Html::endTag('div');
        echo Html::endTag('div');
    ?>
    <?php
        foreach ($team as $value) {
            echo Html::beginTag('div', ['class' => 'item-manage-head item-manage-bottom']);
                echo Html::beginTag('div', ['class' => 'item-manage-headline']).$value->name.Html::endTag('div');
                echo Html::beginTag('div', ['class' => 'lession-time']);
                    echo '<p>已完成：<span style="font-size:25px;">'.array_sum($completedLessionTimes).'</span>学时'.
                    Html::a('', '', ['class' => 'glyphicon glyphicon-eye-open ']).'</p>
                    <p>&nbsp;&nbsp;&nbsp;在做：<span style="font-size:25px;">'.array_sum($undoneLessionTimes).'</span>学时'.
                    Html::a('', '', ['class' => 'glyphicon glyphicon-eye-open ']).'</p>'; 
                echo Html::endTag('div');
            echo Html::endTag('div');
        }
    ?>
    
    
</div>

<?= $this->render('_footer'); ?>

<?php
$js = 
<<<JS
    $('#submit').click(function()
    {
        $('#item-manage-form').submit();
    });
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>