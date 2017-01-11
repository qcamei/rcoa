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
<div class="product-list">
    <div class="modal-dialog list-dialog" role="document">
        <div class="modal-content" style="height: auto; overflow: hidden;">
           <div class="modal-header list-header">
               <div class="col-xs-1 list-operation">
                   <button type="button" id="pl-comeback" class="return">
                       <span aria-hidden="true">&cularr;</span>
                   </button>
               </div>
               <div class="col-xs-9 modal-title list-title"><span><?= $this->title ?></span></div>
               <div class="col-xs-1 list-operation" style="float: right;">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeMyModal();">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
           </div>

           <div class="modal-body list-body">
                <div id="e-pl" class="e-pl"></div>
           </div>
           
            <div id="details" class="details-modal">
                <div class="product-backdrop"></div>
            </div>

           <div class="modal-footer list-footer" style="padding:5px; text-align: inherit;">
               <div class="list-footer-content">
                   <div class="content-left">
                       <span><b>合计:<span class="totals">￥<?= number_format($totals, 2); ?></span></b><span><br/>
                       <span class="lesson">总学时:<?= $lessons; ?>&nbsp;学时</span>
                   </div>
                   <div class="content-right">
                       <a class="btn btn-default btn-sm disabled" id="product-list">已选列表</a>
                       <a class="btn btn-primary btn-sm" id="product-close" onclick="closeMyModal();">确认</a>
                   </div>
               </div>
           </div>
           
       </div>
   </div>
</div>

<?php
$data = json_encode($data);
$js = <<<JS
    var pageList = new Wskeee.demand.PageList({onItemSelected:onItemSelected});
    pageList.init($data);
    /** 单击选择添加产品数量 */
    function onItemSelected(itemdata){
        if(itemdata.type == "content"){
            $('#details .product-backdrop').html("");
            $('#details .product-backdrop').load("/demand/product/view?task_id=$task_id&product_id="+itemdata.id, null,
                function(){
                    $('#details').animate({top:'0px'},'fast','swing');
                }
            )
        }
    }
    /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。  */    
    function closeMyModal(){
        $('.myModal').modal('hide'); 
        $("#demand-task-product-index").load("/demand/product/index?task_id=$task_id");
    }
    /** 格式化所有价钱 */
    format(".totals");
JS;
   $this->registerJs($js, View::POS_READY);
?>

<?php
    DemandAssets::register($this);
    PageListAssets::register($this);
?>

