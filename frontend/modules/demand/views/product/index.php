<?php

use common\models\demand\searchs\DemandTaskProductSearch;
use frontend\modules\demand\assets\DemandAssets;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DemandTaskProductSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/demand', 'Demand Task Products');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="demand-task-product-index">
    
    <h1><?= $this->title ?></h1>
    <?= Html::a('添加', ['list'], ['id' => 'tianjia']); ?>
    
</div>

<div class="demand-task">
    <?= $this->render('/task/_form_model')?>
</div>

<?php
$js = 
<<<JS
    /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。 
    $('.myModal').on('hidden.bs.modal', function(){
        window.location.reload();
    }); */
        
    $('#tianjia').click(function()
    {
        var urlf = $(this).attr("href");
        $('.myModal').modal({remote:urlf});
        return false;
    });
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
?>

