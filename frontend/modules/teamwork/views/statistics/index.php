<?php

use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\assets\TwStatisticsAsset;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\web\View;

/* @var $this View */
/* @var $model ItemManage */
/* @var $dataProvider ActiveDataProvider */


$this->title = Yii::t('rcoa/teamwork', 'Statistics');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container statistics-content">
    <form class="form-horizontal">
        <div class="form-group">
          <label for="dateRange" class="col-sm-2 control-label"><?php echo Yii::t('rcoa/teamwork', 'Statistics-Time-Rang') ?></label>
          <div class="col-sm-10">
            <?php
                echo DateRangePicker::widget([
                    'value'=>$dateRange,
                    'name' => 'dateRange',
                    'presetDropdown' => true,
                    'hideInput' => true,
                    'convertFormat'=>true,
                    'pluginOptions'=>[
                        'locale'=>['format' => 'Y-m-d'],
                    ]
                ]);
            ?>
          </div>
        </div>
        <div class="form-group">
          <label for="team" class="col-sm-2 control-label"><?php echo Yii::t('rcoa/teamwork', 'Statistics-Team') ?></label>
          <div class="col-sm-10">
                <?php 
                echo Select2::widget([
                    'value'=> $team,
                    'name' => 'team',
                    'data' => $teamIds,
                    'options' => [
                        'placeholder' => Yii::t('rcoa/teamwork', 'Statistics-Team-prompt'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>
          </div>
        </div>
        <div class="form-group">
          <label for="team" class="col-sm-2 control-label"><?php echo Yii::t('rcoa/teamwork', 'Statistics-Status') ?></label>
          <div class="col-sm-10">
                <?php 
                echo Select2::widget([
                    'value'=> $status,
                    'name' => 'status',
                    'data' => [ItemManage::STATUS_CARRY_OUT=>'已完成',ItemManage::STATUS_NORMAL=>'在建'],
                    'options' => [
                        'placeholder' => Yii::t('rcoa/teamwork', 'Statistics-Team-prompt'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-success"><?php echo Yii::t('rcoa/teamwork', 'Statistics-Search') ?></button>
          </div>
        </div>
    </form>
    <hr/> 
    <div>
        <div id="itemTypeCanvas" class="chart"></div>
        <div id="itemsCanvas" class="chart"></div>
        <div id="itemChildCanvas" class="chart"></div>
        <div id="teamCanvas" class="chart"></div>
    </div>
    
</div>

<?= $this->render('../default/_footer',[
    'model' => $model,
    'twTool' => $twTool,
]); ?>
<?php
$itemTypes = json_encode($itemTypes);
$items = json_encode($items);
$itemChilds = json_encode($itemChilds);
$teams = json_encode($teams);
$js = <<<JS
        var itemTypeChart = new teamwork.PicChart("按项目类型统计",document.getElementById('itemTypeCanvas'),$itemTypes);
        var itemChart = new teamwork.PicChart("按项目统计",document.getElementById('itemsCanvas'),$items);
        var itemChildChart = new teamwork.PicChart("按子项目统计",document.getElementById('itemChildCanvas'),$itemChilds);
        var teamChart = new teamwork.BarChart("按团队统计",document.getElementById('teamCanvas'),$teams);
JS;
    $this->registerJs($js, View::POS_READY);
    TwStatisticsAsset::register($this);
?>