<?php

use common\models\demand\searchs\DemandTaskProductSearch;
use frontend\modules\demand\assets\PageListAssets;
use yii\data\ActiveDataProvider;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DemandTaskProductSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/demand', 'Demand Task Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modal-header">
    <div class="col-xs-4 modal-operation">
        <button type="button" id="pl-comeback" class="return"><span aria-hidden="true">&cularr;</span></button>
    </div>
    <div class="col-xs-4 modal-title"><span><?= $this->title ?></span></div>
    <div class="col-xs-4 modal-operation">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
</div>

<div class="modal-body">
    <div id="e-pl" class="e-pl"></div>
    <div class="details-modal"></div>
</div>

<div class="modal-footer" style="padding:5px; text-align: inherit;">
    <div class="modal-footer-content">
        <div class="content-left">
            <span><b>合计：￥500000</b><span><br/>
            <span class="lesson">合计学时：243学时</span>
        </div>
        <div class="content-right">
            <button type="button" class="btn btn-default btn-sm" id="product-list">已选列表</button>
            <button type="button" class="btn btn-primary btn-sm" id="product-save">确认</button>
        </div>
    </div>
</div>



<?php
$data = json_encode($data);
$js = <<<JS
   
    var pageList = new Wskeee.demand.PageList({onItemSelected:onItemSelected});
    pageList.init($data);

    function onItemSelected(itemdata){
        if(itemdata.type == "content"){
            $('.details-modal').load("/demand/product/view?id="+itemdata.id).addClass('details-modal-show').attr('style', 'display: block;');
        }
    }

JS;
$this->registerJs($js, View::POS_END);
?>

<?php
    PageListAssets::register($this);
?>

