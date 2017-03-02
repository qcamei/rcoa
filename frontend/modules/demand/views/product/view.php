<?php

use common\models\demand\DemandTask;
use common\models\demand\DemandTaskProduct;
use common\models\product\Product;
use frontend\modules\demand\assets\DemandAssets;
use wskeee\rbac\RbacName;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model DemandTaskProduct */
/* @var $product Product */

$this->title = Yii::t('rcoa/demand', 'Demand Task Products Details');
$this->params['breadcrumbs'][] = $this->title;

?>

<!--<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/demand', 'Demand Tasks'),
                'url' => ['index', 'status' => DemandTask::STATUS_DEFAULT],
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa/demand', 'Demand Task Products Details'),
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>-->

<div class="demand-product-view demand-task">
    <div class="details" style="background-image: url('<?= $product->image ?>')">
        <div class="container details-info">
            <div class="inner">
                <div class="inner-info">
                    <div class="tit"><h2><?= $product->name ?></h2></div>
                    <div class="tag"><span>趣味型</span></div>
                    <div class="inner-desc"><span><?= $product->des ?></span></div>
                    <!--<div class="info"></div>-->
                    <div class="unit-price">
                        <span class="rmb">RMB</span>
                        <span class="unit-price-number"><?= $product->unit_price ?> 元</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    foreach ($product->productDetails as $detail) {
        echo '<div class="details" style="background-image: url('.$detail->details.')"></div>';
    }
    
    ?>
    
</div>


<div class="controlbar">
    <div class="container footer-view">
        <div class="footer-view-btn">
            <div class="footer-view-left">
                <?php
                    echo Html::a(Yii::t('rcoa', 'Back'), ['list', 'task_id' => $model->task_id], ['class' => 'btn btn-default btn-sm back']). ' ';
                    if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE_PRODUCT) && $mark && $model->task->create_by == Yii::$app->user->id){
                        echo Html::a('确认', 'javascript:;', ['id' => 'submit', 'class' => 'btn btn-primary btn-sm']); 
                        echo $this->render('_form', 
                                [
                                    'model' => $model, 
                                    'totals' => $totals,
                                    'unit_price' => $product->unit_price, 
                                    'lessons' => $lessons,
                                    'task_id' => $task_id,
                                    'product_id' => $product_id,
                                ]);
                    }
                ?>
            </div>
        
            <div class="footer-view-right">
                <span class="totals">合计：￥<span id="number"><?= number_format($totals, 2); ?></span>(￥<span id="unit_price"><?= number_format($product->unit_price * $model->number, 2) ?></span>)</span></br>
                    <span class="lessons">
                    <?= '学时：<span class="lessons-big"><span class="lessons-small">'.$lessons.'</span>/'.$model->task->lesson_time.'</span>';?>                    
                    </span>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<<JS
   $(window).resize(function(){
        size();
    });
    size();
    function size(){
        var width = $(document.body).width(),
        height = $(document.body).height();
        $(".details").css({width:width,height:height, display:"block"});
    }
    /** 提交表单 */
    $('#submit').click(function()
    {
        $('#demand-task--product-form').submit();
    });
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    DemandAssets::register($this);
    //PageListAssets::register($this);
?>