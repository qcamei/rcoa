<?php

use common\widgets\charts\ChartAsset;
use frontend\modules\need\assets\ModuleAssets;
use kartik\daterange\DateRangePicker;
use kartik\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', '{Statistics}-{Cost}',[
    'Statistics' => Yii::t('app', 'Statistics'),
    'Cost' => Yii::t('app', 'Cost'),
]);

$radioType = [
    '0' => '按分类',
    '1' => '按人',
    '2' => '按内容',
]

?>

<div class="statistics statistics-cost">
    <form class="form-horizontal">
        <!--时间段-->
        <div class="form-group">
          <label for="dateRange" class="col-sm-1 control-label"><?php echo Yii::t('app', 'Time Slot') ?>：</label>
          <div class="col-sm-11">
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
                                                                    '第一季' => ["moment().startOf('Q').quarter(1,'quarter')","moment().endOf('Q').quarter(1,'quarter')"],
                                                                    '第二季' => ["moment().startOf('Q').quarter(2,'quarter')","moment().endOf('Q').quarter(2,'quarter')"],
                                                                    '第三季' => ["moment().startOf('Q').quarter(3,'quarter')","moment().endOf('Q').quarter(3,'quarter')"],
                                                                    '第四季' => ["moment().startOf('Q').quarter(4,'quarter')","moment().endOf('Q').quarter(4,'quarter')"],
                            Yii::t('rcoa/teamwork', "Statistics-First-Half-Year") => ["moment().startOf('year')", "moment().startOf('year').add(5,'month').endOf('month')"],
                            Yii::t('rcoa/teamwork', "Statistics-Next-Half-Year") => ["moment().startOf('year').add(6,'month')", "moment().endOf('year')"],
                            Yii::t('rcoa/teamwork', "Statistics-Full-Year") => ["moment().startOf('year')", "moment().endOf('year')"],
                        ]
                    ],
                    
                ]);
            ?>
          </div>
        </div>
        
        <!--统计方式-->
        <div class="form-group">
            <label for="type" class="col-sm-1 control-label"><?php echo Yii::t('app', '{Statistics}{Mode}', [
                'Statistics' => Yii::t('app', 'Statistics'),
                'Mode' => Yii::t('app', 'Mode'),
            ]) ?>：</label>
            <div  class="col-sm-11">
                <?php 
                    echo Html::radioList('type', $type, $radioType, [
                        'class' => 'radiolist',
                        'itemOptions' => [
                            'class' => 'radiotype'
                        ]
                    ]);
                ?>
            </div>
        </div>
        <!--提交按钮-->
        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-11">
                <button type="submit" class="btn btn-success"><?php echo Yii::t('app', 'Statistics') ?></button>
            </div>
        </div>
    </form>
    <hr/>
    <!--统计结果-->
    <div>
        <div class="summar-title">
            <i class="fa fa-bar-chart"></i>&nbsp;总成本：
            <span class="num">￥<?= empty($totalCost['total_cost']) ? '0.00' : $totalCost['total_cost']; ?></span>
        </div>
        <br/>
        <?php if($type == 0): ?>
            <div id="businessCanvas" class="chart"></div>
            <div id="layerCanvas" class="chart"></div>
            <div id="professionCanvas" class="chart"></div>
        <?php elseif ($type == 1): ?>
            <div id="presonalCanvas" class="chart"></div>
        <?php elseif ($type == 2): ?>
            <div id="workitemCanvas" class="chart"></div>
        <?php endif;?>
    </div>
</div>
<?php
$business = json_encode($business);     //行业
$layer = json_encode($layer);           //层次/类型
$profession = json_encode($profession); //专业/工种
$presonal = json_encode($presonal);     //人
$workitems = json_encode($workitems);   //工作项
$items = json_encode($items);           

$js = <<<JS
        
    if($type === 0){
        var businessChart = new ccoacharts.PicChart({title:"按行业统计",itemLabelFormatter:'{b} ( {c} 元) {d}%',tooltipFormatter:'{a} <br/>{b} : {c}元 ({d}%)'},document.getElementById('businessCanvas'),$business);
        var layerChart = new ccoacharts.PicChart({title:"按层次/类型统计",itemLabelFormatter:'{b} ( {c} 元) {d}%',tooltipFormatter:'{a} <br/>{b} : {c}元 ({d}%)'},document.getElementById('layerCanvas'),$layer);
        var professionChart = new ccoacharts.PicChart({title:"按专业/工种统计",itemLabelFormatter:'{b} ( {c} 元) {d}%',tooltipFormatter:'{a} <br/>{b} : {c}元 ({d}%)'},document.getElementById('professionCanvas'),$profession);
    }else if($type === 1){
        var presonalChart = new ccoacharts.BarChart({title:"按人统计",itemLabelFormatter:'{c} 元'},document.getElementById('presonalCanvas'),$presonal);
    }else if($type === 2){
        var workitemCanvas = new ccoacharts.MultiBarChart({title:"",itemLabelFormatter:'{c}元'},document.getElementById('workitemCanvas'),$workitems,$items);
    }
JS;
    $this->registerJs($js, View::POS_READY);
    ChartAsset::register($this);
    ModuleAssets::register($this);
?>