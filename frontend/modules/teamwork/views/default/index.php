<?php

use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TwAsset;
use yii\data\ActiveDataProvider;
use yii\web\View;

/* @var $this View */
/* @var $model ItemManage */
/* @var $dataProvider ActiveDataProvider */


$this->title = Yii::t('rcoa/teamwork', 'Item Manages');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container item-manage-index item-manage">
    <div style="background-color: rgba(243, 123, 83, 1);width: 100%; height: 180px;margin-top: 20px;">
        <dl>    
            <dt style="padding:10px;display: block;font-size: 20px;color: #fff">课程中心</dt>
            <dd  style="padding-left:50px;color: #fff;">已完成：
                <span style="font-size:35px">2000</span>&nbsp;
                <span>学时</span>&nbsp;&nbsp;
                <a style="font-size: 25px;color: #fff" class="glyphicon glyphicon-eye-open"></a>
            </dd>
            <dd  style="padding-left:50px;color: #fff">在&nbsp;&nbsp;&nbsp;做：
                <span style="font-size:35px">2000</span>&nbsp;
                <span>学时</span>&nbsp;&nbsp;
                <a style="font-size: 25px;color: #fff" class="glyphicon glyphicon-eye-open"></a>
            </dd>
        </dl>
    </div>
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