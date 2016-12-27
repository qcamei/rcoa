<?php

use common\models\demand\searchs\DemandTaskProductSearch;
use frontend\modules\demand\assets\DemandAssets;
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
    <div class="col-xs-1 modal-operation">
        <button type="button" id="pl-comeback" class="return"><span aria-hidden="true">&cularr;</span></button>
    </div>
    <div class="col-xs-9 modal-title"><span><?= $this->title ?></span></div>
    <div class="col-xs-1 modal-operation" style="float: right;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
</div>

<div class="modal-body">
    <div id="e-pl" class="e-pl"></div>
    <div id="details" class="details-modal">
        <div class="product-backdrop"></div>
    </div>
</div>

<div class="modal-footer" style="padding:5px; text-align: inherit;">
    <div class="modal-footer-content">
        <div class="content-left">
            <span><b>合计：￥500000</b><span><br/>
            <span class="lesson">合计学时：243学时</span>
        </div>
        <div class="content-right">
            <a class="btn btn-default btn-sm disabled" id="product-list">已选列表</a>
            <a class="btn btn-primary btn-sm" id="product-close">确认</a>
        </div>
    </div>
</div>



<?php
$data = json_encode($data);
$js = <<<JS
    /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。 */    
    $("#product-close").click(function(){
        $('.myModal').modal('hide'); 
        $('.myModal').on('hidden.bs.modal', function(){
            window.location.reload();
        });
    });     
        
    var pageList = new Wskeee.demand.PageList({onItemSelected:onItemSelected});
    pageList.init($data);
      
    /** 单击选择添加产品数量 */
    function onItemSelected(itemdata){
        if(itemdata.type == "content"){
            $('#details .product-backdrop').load("/demand/product/view?task_id=$task_id&product_id="+itemdata.id,null,
                function(){
                    $('#details').animate({top:'0px'},'fast','swing');
                }
            )
        }
    }
    
JS;
$this->registerJs($js, View::POS_END);
?>

<?php
    PageListAssets::register($this);
    DemandAssets::register($this);
?>

