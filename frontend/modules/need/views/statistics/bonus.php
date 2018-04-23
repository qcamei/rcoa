<?php

use common\widgets\charts\ChartAsset;
use frontend\modules\need\assets\ModuleAssets;
use kartik\daterange\DateRangePicker;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', '{Statistics}-{Bonus}',[
    'Statistics' => Yii::t('app', 'Statistics'),
    'Bonus' => Yii::t('app', 'Bonus'),
]);

?>

<div class="statistics statistics-bonus">
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
            <i class="fa fa-bar-chart"></i>&nbsp;总绩效：
            <span class="num">￥<?= empty($totalBonus['total_bonus']) ? '0.00' : $totalBonus['total_bonus']; ?></span>
        </div>
        <br/>
        <div id="bonusCanvas" class="chart"></div>
    </div>
</div>

<?php
$bonus = json_encode($bonus);

$js = <<<JS
        var presonalChart = new ccoacharts.BarChart({title:"",itemLabelFormatter:'{c} 元'},document.getElementById('bonusCanvas'),$bonus);
JS;
    $this->registerJs($js, View::POS_READY);
    ChartAsset::register($this);
    ModuleAssets::register($this);
?>