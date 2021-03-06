<?php

use common\models\demand\DemandTaskProduct;
use common\models\demand\searchs\DemandTaskProductSearch;
use frontend\modules\demand\assets\DemandAssets;
use wskeee\rbac\RbacName;
use yii\data\ActiveDataProvider;
use yii\web\View;

/* @var $this View */
/* @var $model DemandTaskProduct */
/* @var $searchModel DemandTaskProductSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/demand', 'Demand Task Products');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="demand-task-product-index">

    <?php if(!empty($data)): ?>
        <?php foreach ($data as $value): ?>
        <div class="product-index">
            <a  href="/demand/product/view?task_id=<?= $value['task_id'] ?>&product_id=<?= $value['product_id'] ?>">
                <div class="product-index-header"><img src="<?= $value['image'] ?>"></div>
                <div class="product-index-body"
                    <p>【<?= $value['name'] ?>】</p>
                    <p class="des"><?= $value['des'] ?></p>
                    <p>
                        <span class="price"><?= $value['currency'].number_format($value['unit_price'], 2) ?></span>
                        <span class="number">×<?= $value['number'] ?></span>
                        <span class="total-price hidden-xs"><?= $value['currency'].number_format($totalPrice[$value['product_id']], 2) ?></p></span>
                    </p>
                </div>
                <div class="product-index-footer">
                    <?php if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_DELETE_PRODUCT) && $mark && $model->task->create_by == Yii::$app->user->id):?>
                    <a class="btn btn-danger btn-sm" data_t="<?= $value['task_id'] ?>" data_p="<?= $value['product_id'] ?>" onclick="deleteproduct($(this));">删除</a>
                    <?php endif;?>
                </div>
            </a>
        </div>
        <?php endforeach;?>
    <?php else: ?>
        <div class="product-index">没有找到数据。</div>
    <?php endif;?>    
        
    
    
</div>
<div class="total">
    <p><b>合计总额：<span class="totals">￥<?= number_format($totals, 2); ?></span></b></p>
    <p><span class="lesson">总学时：<?= $lessons ?>&nbsp;学时</span></p>
</div>


<?php
$js = 
<<<JS
    
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<script type="text/javascript">
    /** 单击产品列表显示产品详情  */
    function viewproduct(obj){
        var data_t = $(obj).attr("data_t");
        var data_p = $(obj).attr("data_p");
        $(".myModal").html("");
        $('#add').tooltip('hide');
        $(".myModal").modal('show');
        $(".myModal").html('<div id="details" class="details-dialog" style="margin:0 auto;"></div>');
        $("#details").load("/demand/product/view?task_id="+data_t+"&product_id="+data_p+"&sign=1",null,
            function(){
               $('#details').animate({top:'-120px'},'fast','swing');
            }
        );
        return false;
    }
    /** 单击删除产品 */
    function deleteproduct(obj){
        var data_t = $(obj).attr("data_t");
        var data_p = $(obj).attr("data_p");
        $.post("/demand/product/deletes?task_id="+data_t+"&product_id="+data_p,function(data){
            if(data['type'] == 1){
                alert(data['error']);
                //$(obj).parent().parent().remove();
                $("#demand-task-product-index").load("/demand/product/index?task_id="+data_t);
            }else{
                alert(data['error']);
            }
        });
    }
    /** 格式化所有价钱 */
    format(".price");
    format(".total-price");
    format(".totals");
    /** 价格格式化 */
    function format(obj){
        $(obj).each(function(){
            var con = trim($(this).html()).split('￥');
            $(this).html('<span class="big">' + $(this).html().split('.')[0] + '.</span><span class="small">' + $(this).html().split('.')[1] + '</span>');
        });
    }
    /** 正则匹配 */
    function trim(str){ 
　　     return str.replace(/(^\s*)|(\s*$)/g, "");
　　}
</script>

<?php
    DemandAssets::register($this);
?>

