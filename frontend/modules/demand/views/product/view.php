<?php

use common\models\demand\DemandTaskProduct;
use frontend\modules\demand\assets\PageListAssets;
use yii\web\View;

/* @var $this View */
/* @var $model DemandTaskProduct */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Task Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="details-backdrop"></div>
<div class="details-dialog">
    <div class="details-content">
        <div class="details-header">
            <button id="close" type="button" class="close" ><span>&times;</span></button>
        </div>

        <div class="details-body">
            <?= $model->id?>
        </div>

        <div class="details-footer">
            <div class="footer-content">
                <div class="content-left">
                    <span><b>合计：￥500000</b><span><br/>
                </div>
                <div class="content-right">
                    <button type="button" class="btn btn-default btn-sm" id="product-list">已选列表</button>
                    <button type="button" class="btn btn-primary btn-sm" id="product-save">确认</button>
                </div>
            </div>
        </div>
    </div> 
</div>
<?php
$js = <<<JS
    $("#close").click(function(){
        $(".details-modal").removeClass('details-modal-show').attr('style','display: none;');
    });
JS;
    $this->registerJs($js, View::POS_END);
?>

<?php
    //PageListAssets::register($this);
?>