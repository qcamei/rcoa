<?php

use common\models\demand\DemandTask;
use common\models\demand\searchs\DemandTaskProductSearch;
use frontend\modules\demand\assets\DemandAssets;
use frontend\modules\demand\assets\PageListAssets;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $searchModel DemandTaskProductSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $taskModel DemandTask */

$this->title = Yii::t('rcoa/demand', 'Demand Task Products List');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/demand', 'Demand Tasks'),
                'url' => ['task/index', 'status' => DemandTask::STATUS_DEFAULT],
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa/demand', 'Demand Task Products List'),
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container demand-product-list has-title  demand-task">
    <button type="button" id="pl-comeback" class="return">
        <span aria-hidden="true">&cularr;</span>
    </button>
    <div class="product-list">
        <div id="e-pl" class="e-pl"></div>
    </div>
</div>

<div class="controlbar">
    <div class="container">
        <div class="footer-view-btn">
            <div class="footer-view-left">
                <?= Html::a(Yii::t('rcoa', 'Back'), ['task/view', 'id' => $task_id], ['class' => 'btn btn-default']) ?>
            </div>
        
            <div class="footer-view-right">
                    <span class="totals">合计：￥<span id="number"><?= number_format($totals, 2); ?></span></span></br>
                    <span class="lessons">
                    <?= '学时：<span class="lessons-big"><span class="lessons-small">'.$lessons.'</span>/'.$taskModel->lesson_time.'</span>';?>                    
                    </span>
            </div>
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
           location.href = "/demand/product/view?task_id=$task_id&product_id="+itemdata.id; 
        }
    }
    
JS;
   $this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
    PageListAssets::register($this);
?>

