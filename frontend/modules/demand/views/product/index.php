<?php

use common\models\demand\searchs\DemandTaskProductSearch;
use frontend\modules\demand\assets\DemandAssets;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DemandTaskProductSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/demand', 'Demand Task Products');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="demand-task-product-index">

    <?php if(!empty($data)): ?>
        <?php foreach ($data as $value): ?>
        <div class="product-list">
            <?php if(isset($mark) && $mark == true): ?>
            <a data_t="<?= $value['task_id'] ?>" data_p="<?= $value['product_id'] ?>" onclick="viewproduct($(this));">
                <div class="product-list-header"><img src="<?= $value['image'] ?>"></div>
                <div class="product-list-body"
                    <p>【<?= $value['name'] ?>】</p>
                    <p class="des"><?= $value['des'] ?></p>
                    <p>
                        <span class="price"><?= $value['currency'].number_format($value['unit_price'], 2) ?></span>
                        <span class="number">×<?= $value['number'] ?></span>
                    </p>
                </div>
                <div class="product-list-footer">
                    <a class="btn btn-danger btn-sm" data_t="<?= $value['task_id'] ?>" data_p="<?= $value['product_id'] ?>" onclick="deleteproduct($(this));">删除</a>
                </div>
            </a>
            <?php else: ?>
                <div class="product-list-header"><img src="<?= $value['image'] ?>"></div>
                <div class="product-list-body"
                    <p>【<?= $value['name'] ?>】</p>
                    <p class="des"><?= $value['des'] ?></p>
                    <p>
                        <span class="price"><?= $value['currency'].number_format($value['unit_price'], 2) ?></span>
                        <span class="number">×<?= $value['number'] ?></span>
                        <span class="total-price"><?= $value['currency'].number_format($totalPrice[$value['product_id']], 2) ?></p></span>
                    </p>
                </div>
            <?php endif;?> 
        </div>
        <?php endforeach;?>
    <?php else: ?>
        <div class="product-list">没有找到数据。</div>
    <?php endif;?>    
        
    
    
</div>
<div class="total">
    <p><b>合计总额：<span class="totals">￥<?= number_format($totals, 2); ?></span></b></p>
    <p><span class="lesson">总学时：<?= $lessons ?>&nbsp;学时</span></p>
</div>
<div class="demand-task">
    <?= isset($mark) && $mark == true ? $this->render('/task/_form_model') : '' ; ?>
</div>

<?php
$js = 
<<<JS
   /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。 
    $('.myModal').on('hidden.bs.modal', function(){
        window.location.reload();
    });*/       
JS;
    //$this->registerJs($js,  View::POS_END);
?>

<script type="text/javascript">
    /** 单击添加按钮显示产品列表 模态框 */ 
    $('#add').click(function()
    {
        var urlf = $(this).attr("href");
        $(".myModal").modal('show');
        $(".myModal").load(urlf);
        return false;
    }); 
    /** 单击产品列表显示产品详情  */
    function viewproduct(obj){
        var data_t = $(obj).attr("data_t");
        var data_p = $(obj).attr("data_p");
        $(".myModal").modal('show');
        $(".myModal .modal-dialog .modal-content").load("/demand/product/list?task_id="+data_t,null,function(){
            $("#details .product-backdrop").load("/demand/product/view?task_id="+data_t+"&product_id="+data_p,null,
                function(){
                    $('#details').animate({top:'0px'},'fast','swing');
                }
            );
        });
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
                $("#demand-task-product-list").load("/demand/product/index?task_id="+data_t+"&mark=1");
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

