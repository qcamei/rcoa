<?php

use common\models\demand\DemandTaskProduct;
use common\models\product\Product;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model DemandTaskProduct */
/* @var $product Product */

?>

    <div class="details-dialog">
        <div class="details-content">
            <div class="details-header">
                <button id="close" type="button" class="close" ><span>&times;</span></button>
            </div>

            <div class="details-body">
                <div class="body-header">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-5 edge">
                        <?= Html::img([$product->image]) ?>
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-8 col-xs-7 edge">
                        <p>【<?= $product->name?>】</p>
                        <p style="color: #ccc;"><?= $product->des ?></p>
                        <p style="color: #f00;"><?= $product->currency.$product->unit_price ?></p>
                    </div>
                </div>
                <div class="body-footer">
                    <ul class="nav nav-tabs" role="tablist" id="myTab">
                        <li role="presentation" class="active"><a href="#home" role="tab" data-toggle="tab">详情</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="home">
                            <p><?= $product->productDetail->details ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="details-footer">

                <div class="footer-left">
                    <p><b>合计:<span class="totals">￥<?= number_format($totals, 2); ?></span></b></p>
                </div>

                <div class="footer-right">

                    <?= Html::a('确认', 'javascript:;', ['id' => 'product-save', 'class' => 'btn btn-primary btn-sm', 'style' => 'float: right; margin-left:5px;'])?>

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>

            </div>
        </div> 
    </div>
<?php
$js = <<<JS
    /** 隐藏产品详情 */    
    $("#close").click(function(){
        $('#details').animate({top:'1000px'},'fast','swing');
    });
    
    /** 提交表单操作 */
    $("#product-save").click(function(){
        $.post("/demand/product/save?task_id=$task_id&product_id=$product_id", $('#demand-task--product-form').serialize(), function(data){
            if(data['type'] == 1){
                alert(data['error']);
                $('#details').animate({top:'1000px'},'fast','swing');
                $(".myModal .modal-dialog .modal-content").load("/demand/product/list?task_id=$task_id");
            }else{
                alert(data['error']);
            }
        });
    });
        
    /** 格式化所有价钱 */
    format(".totals");
JS;
    $this->registerJs($js, View::POS_END);
?>

<?php
    //PageListAssets::register($this);
?>