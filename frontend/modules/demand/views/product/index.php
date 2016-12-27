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

<?= isset($mark) && $mark == true ? '<h5><b>'.$this->title.'</b></h5>' : '<h4>'.$this->title.'</h4>'; ?>

<div class="demand-task-product-index">
    <div class="col-lg-11 col-md-11" style="padding:0px;">
        <?= isset($mark) && $mark == true ? 
                Html::a('添加', ['product/list', 'task_id' => $task_id], ['id' => 'add' ,'class' => 'btn btn-success btn-sm']) : ''; ?>
        <div id="demand-task-product-list"></div>
    </div>
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
    });*/
    
    /** 判断是否为刚开始创建课程产品 */
    if($mark == 1){
       var urlf = $("#add").attr("href");
       $(".myModal").modal('show');
       $(".myModal .modal-dialog .modal-content").load(urlf) 
    }
        
    /** 单击添加按钮显示产品列表 模态框 */ 
    $('#add').click(function()
    {
        var urlf = $(this).attr("href");
        $(".myModal").modal('show');
        $(".myModal .modal-dialog .modal-content").load(urlf)
        return false;
    });
    
    /** 加载已经添加的产品 */
    $.get("/demand/product/index?task_id=$task_id", function(data){
        if(data.type == 1){
            $.each(data.data, function(index, value){
                var dataHtml = '<div class="product-list">'+
                    '<a data_t="'+value['task_id']+'" data_p="'+value['product_id']+'" onclick="viewproduct($(this));">'+
                    '<div class="product-list-header"><img src="'+value['image']+'"></div>'+
                    '<div class="product-list-body"><p>【'+value['name']+'】</p><p class="des">'+value['des']+'</p><p class="price">'+
                    value['currency']+value['unit_price']+'<span class="number">×'+value['number']+'</span></p></div></a>'+
                    '<div class="product-list-footer">'+
                    '<a class="btn btn-danger btn-sm" data_t="'+value['task_id']+'" data_p="'+value['product_id']+'" onclick="deleteproduct($(this));">删除</a>'+
                    '</div></div>';  

                $(dataHtml).appendTo("#demand-task-product-list");
            });
        }
    });
       
JS;
    $this->registerJs($js,  View::POS_READY);
?>
<script type="text/javascript">
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
                $(obj).parent().parent().remove();
            }else{
                alert(data['error']);
            }
        });
    }
   
</script>
<?php
    DemandAssets::register($this);
?>

