<?php

use common\widgets\charts\ChartAsset;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\web\View;

/* @var $this View */

$this->title = '任务-主页';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="worksystem">
    
    <div class="head-top"></div>
    
    <div class="container content">
        
        <div class="main-title">
            <span class="icon statistics-disable-icon"></span>本月已完成工作量统计
        </div>
        
        <div class="title">总任务数：<span class="title-number"><?= number_format($counts) ?></span>个</div>
        <div id="datas-count-canvas" class="chart"></div>
        
        <div class="title">总成本：<span class="title-number"><?= number_format($countCost,2) ?></span>元</div>
        <div id="datas-totalCost-canvas" class="chart"></div>
        
        <div class="title">总外包成本：<span class="title-number"><?= number_format($epibolyCost,2) ?></span>元</div>
        <div id="datas-epibolyCost-canvas" class="chart"></div>
        
        <div class="title">团队内容统计：</div>
        <div id="datas-teamCost-canvas" class="chart"></div>
        
    </div>
    
</div>

<?php

$datas_count = json_encode($datas_count);
$datas_totalCost = json_encode($datas_totalCost);
$datas_epibolyCost = json_encode($datas_epibolyCost);
$datas_teamCost = json_encode($datas_teamCost);
$types = json_encode($types);
$js = <<<JS
        var itemTypeChart = new ccoacharts.PicChart({title:"",itemLabelFormatter:'{b} ( {c} 个) {d}%',tooltipFormatter:'{a} <br/>{b} : {c}个 ({d}%)'},document.getElementById('datas-count-canvas'),$datas_count);
        var itemChart = new ccoacharts.PicChart({title:"",itemLabelFormatter:'{b} ( {c} 元) {d}%',tooltipFormatter:'{a} <br/>{b} : {c}元 ({d}%)'},document.getElementById('datas-totalCost-canvas'),$datas_totalCost);
        var itemChildChart = new ccoacharts.PicChart({title:"",itemLabelFormatter:'{b} ( {c} 元) {d}%',tooltipFormatter:'{a} <br/>{b} : {c}元 ({d}%)'},document.getElementById('datas-epibolyCost-canvas'),$datas_epibolyCost);
        var teamChart = new ccoacharts.MultiBarChart({title:"",itemLabelFormatter:'{c}元'},document.getElementById('datas-teamCost-canvas'),$datas_teamCost,$types);

        
       
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
    ChartAsset::register($this);
?>