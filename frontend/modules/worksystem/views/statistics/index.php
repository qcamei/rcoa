<?php

use common\widgets\charts\ChartAsset;
use frontend\modules\worksystem\assets\WorksystemAssets;
use kartik\daterange\DateRangePicker;
use yii\web\View;

/* @var $this View */

$this->title = '任务-统计';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="worksystem container statistics-content">
    
    <form class="form-horizontal">
        <div class="form-group">
          <label for="dateRange" class="col-sm-2 control-label"><?php echo Yii::t('rcoa/multimedia', 'Statistics-Year') ?></label>
          <div class="col-sm-10">
              <?php
                echo DateRangePicker::widget([
                    'value'=>$dateRange,
                    'name' => 'dateRange',
                    //'presetDropdown' => true,
                    'hideInput' => true,
                    'convertFormat'=>true,
                    'pluginOptions'=>[
                        'locale'=>['format' => 'Y-m-d'],
                        'allowClear' => true,
                        'ranges' => [
                            Yii::t('rcoa/teamwork', "Statistics-Prev-Week") => ["moment().startOf('week').subtract(1,'week')", "moment().endOf('week').subtract(1,'week')"],
                            Yii::t('rcoa/teamwork', "Statistics-This-Week") => ["moment().startOf('week')", "moment().endOf('week')"],
                            Yii::t('rcoa/teamwork', "Statistics-Prev-Month") => ["moment().startOf('month').subtract(1,'month')", "moment().endOf('month').subtract(1,'month')"],
                            Yii::t('rcoa/teamwork', "Statistics-This-Month") => ["moment().startOf('month')", "moment().endOf('month')"],
                            Yii::t('rcoa/teamwork', "Statistics-First-Half-Year") => ["moment().startOf('year')", "moment().startOf('year').add(5,'month').endOf('month')"],
                            Yii::t('rcoa/teamwork', "Statistics-Next-Half-Year") => ["moment().startOf('year').add(6,'month')", "moment().endOf('year')"],
                            Yii::t('rcoa/teamwork', "Statistics-Full-Year") => ["moment().startOf('year')", "moment().endOf('year')"],
                        ]
                    ],
                    
                ]);
            ?>
          </div>
        </div>
        
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-success"><?php echo Yii::t('rcoa/multimedia', 'Statistics-Submit') ?></button>
          </div>
        </div>
    </form>
    <hr/> 
    

    <div class="container content">

        <div class="main-title">
            <span class="icon statistics-disable-icon"></span>已完成工作量统计
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