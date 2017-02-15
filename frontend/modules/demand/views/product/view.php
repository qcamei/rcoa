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
    <div class="details-header" style="background-image: url('<?= $product->image ?>')">
        <div class="container details-info">
            <div class="inner">
                <h2 class="tit"><?= $product->name ?></h2>
                <div class="desc"><span>学员随报随学，定期开班</span></div>
                <div class="info"><?= $product->des ?></div>
                <div class="price">
                    <span class="rmb">RMB</span>
                    <span class="number"><?= $product->unit_price ?> 元</span>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="controlbar">
    <div class="container footer-view">
        <div class="footer-view-btn">
            <div class="footer-view-left">
                <span class="totals">合计：￥<?= number_format($totals, 2); ?></span></br>
                <?php if($lessons < 0){
                    echo '<span class="overtime">';
                    echo '超出：'.abs($lessons).'学时';
                }
                else{        
                    echo '<span class="lessons">';
                    echo '剩下：'.$lessons.'学时';
                }
                ?>                    
                </span>
            </div>
        </div>
        <div class="footer-view-right">
            <?php
                if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE_PRODUCT) && $mark && $model->task->create_by == Yii::$app->user->id){
                    echo Html::a('确认', 'javascript:;', ['id' => 'product-save', 'class' => 'btn btn-primary btn-sm', 'style' => 'float: right; margin-left:5px;']); 
                    echo $this->render('_form', ['model' => $model,]);
                }
            ?>
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
        var width = $('.wrap').width(),
        height = $('.wrap').height();
        $(".details-header").css({width:width,height:height});
    }
    
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    DemandAssets::register($this);
    //PageListAssets::register($this);
?>