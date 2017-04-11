<?php

use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use common\widgets\charts\ChartAsset;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model ItemManage */
/* @var $dataProvider ActiveDataProvider */


$this->title = Yii::t('rcoa/teamwork', 'Statistics');
$this->params['breadcrumbs'][] = $this->title;

$statisticsName = ($type == 0 ? '学时':'元');
?>
<div class="container statistics-content">
    <div class="btn-group">
        <?php echo Html::a("按时长统计", Url::to('/demand/statistics?type=0'), ['class'=>"btn btn-default".($type == 0 ? ' active': '')]); ?>
        <?php echo Html::a('按花费统计', Url::to('/demand/statistics?type=1'), ['class'=>'btn btn-default'.($type == 1 ? ' active': '')]); ?>
    </div>
    <hr/> 
    <form class="form-horizontal">
        <div class="form-group">
          <label for="dateRange" class="col-sm-2 control-label"><?php echo Yii::t('rcoa/teamwork', 'Statistics-Time-Rang') ?></label>
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
          <label for="item_type_id" class="col-sm-2 control-label"><?php echo Yii::t('rcoa/teamwork', 'Item Type') ?></label>
          <div class="col-sm-10">
                <?php 
                echo Select2::widget([
                    'value'=> $item_type_id,
                    'name' => 'item_type_id',
                    'data' => $item_type_ids,
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
          <label for="item_id" class="col-sm-2 control-label"><?php echo Yii::t('rcoa/teamwork', 'Item') ?></label>
          <div class="col-sm-10">
                <?php 
                echo Select2::widget([
                    'value'=> $item_id,
                    'name' => 'item_id',
                    'id' => 'item_id',
                    'data' => $item_ids,
                    'options' => [
                        'placeholder' => Yii::t('rcoa/teamwork', 'Statistics-Team-prompt'),
                        'onchange'=>'wx_one(this)',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>
          </div>
        </div>
        
        
        <div class="form-group">
          <label for="item_child_id" class="col-sm-2 control-label"><?php echo Yii::t('rcoa/teamwork', 'Item Child') ?></label>
          <div class="col-sm-10">
                <?php 
                echo Select2::widget([
                    'name' => 'item_child_id',
                    'id' => 'item_child_id',
                    'value'=>$item_child_id,
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
                    'data' => CourseManage::$statusName,
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
        <div class="summar-title">
            <span class="summar-icon"></span>结果:
            <span  class="num"><?= number_format($allValues) ?></span> <?= $statisticsName ?>
            <span class="num"><?= number_format($allCourse) ?></span> 门课程
        </div>
        <br/>
        <div id="itemTypeCanvas" class="chart"></div>
        <div id="itemsCanvas" class="chart"></div>
        <div id="itemChildCanvas" class="chart"></div>
        <div id="teamCanvas" class="chart"></div>
    </div>
    
</div>
<script type="text/javascript">
    function wx_one(e,select){
	$("#item_child_id").html("");
	$("#select2-item_child_id-container").html("全部");
	$.post("/framework/api/search?id="+$(e).val(),function(data)
        {
            var selectedName = "";
            $('<option/>').appendTo($("#item_child_id"));
            $.each(data['data'],function()
            {
                $('<option>').val(this['id']).text(this['name']).appendTo($("#item_child_id"));
                if(select && select == this['id'])
                    selectedName = this['name'];
            });
            if(select)
            {
                $("#item_child_id").val(select);
                $("#select2-item_child_id-container").html(selectedName);
            }
	});
    }
</script>

<?php
$itemTypes = json_encode($itemTypes);
$items = json_encode($items);
$itemChilds = json_encode($itemChilds);
$teams = json_encode($teams);

$js = <<<JS
        var itemTypeChart = new ccoacharts.PicChart({title:"按行业统计",itemLabelFormatter:'{b} ( {c} $statisticsName) {d}%',tooltipFormatter:'{a} <br/>{b} : {c}$statisticsName ({d}%)'},document.getElementById('itemTypeCanvas'),$itemTypes);
        var itemChart = new ccoacharts.PicChart({title:"按层次/类型统计",itemLabelFormatter:'{b} ( {c} $statisticsName) {d}%',tooltipFormatter:'{a} <br/>{b} : {c}$statisticsName ({d}%)'},document.getElementById('itemsCanvas'),$items);
        var itemChildChart = new ccoacharts.PicChart({title:"按专业/工种统计",itemLabelFormatter:'{b} ( {c} $statisticsName) {d}%',tooltipFormatter:'{a} <br/>{b} : {c}$statisticsName ({d}%)'},document.getElementById('itemChildCanvas'),$itemChilds);
        var teamChart = new ccoacharts.BarChart({title:"按团队统计",itemLabelFormatter:'{c}$statisticsName'},document.getElementById('teamCanvas'),$teams);
        
        var item_id = Number("$item_id") == "" ? -1 : Number("$item_id");
        if(item_id!=-1)
        {
            wx_one($('#item_id'),Number("$item_child_id"));
        }
JS;
    $this->registerJs($js, View::POS_READY);
    ChartAsset::register($this);
?>

