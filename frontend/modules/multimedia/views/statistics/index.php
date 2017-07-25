<?php

use frontend\modules\multimedia\assets\StatisticsAsset;
use frontend\modules\multimedia\utils\MultimediaConvertRule;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$this->title = '开发-'.Yii::t('rcoa/multimedia', 'Multimedia Statistics');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container statistics-content">
    <div class="btn-group">
        <?php echo Html::a('标准工作量', Url::to('/multimedia/statistics?type=0'), ['class'=>'btn btn-default active']); ?>
        <?php echo Html::a('成品时长', Url::to('/multimedia/statistics?type=1'), ['class'=>'btn btn-default']); ?>
    </div>
    <hr/> 
    <form class="form-horizontal">
        <input type="hidden" name="type" value="0"/>
        <div class="form-group">
          <label for="dateRange" class="col-sm-2 control-label"><?php echo Yii::t('rcoa/multimedia', 'Statistics-Year') ?></label>
          <div class="col-sm-10">
              <?php
              echo DatePicker::widget([
                        'id' => 'date',
                        'name' => 'date',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'value' => $date,
                        'readonly' => true,
                        'options' => [
                            'placeholder' => 'Select issue date ...',
                            //'onchange'=>'dateChange($(this).val())',
                            ],
                        'pluginOptions' => [
                            'format' => 'yyyy-m',
                            'todayHighlight' => true,
                            'minViewMode' => 1,
                        ]
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
    <div>
        <div class="summar-title">
            <span class="summar-icon"></span>总工作量:
            <span  class="num"><?= $allWorkload ?></span>
        </div>
        <div class="rule">
            换算规则：
            <span>
                <?php
                    foreach($rules as $id => $name)
                        echo "<span class='rule-name'>$name</span><span class='rule-proportion'>(1分钟成品 = ".
                            MultimediaConvertRule::getInstance()->getRuleProportion($id)."个标准工作量)</span>";
                ?>
            </span>
        </div>
        <br/>
        <div class="chart-title">团队</div>
        <div id="datas_team" class="chart"></div>
        <div class="chart-title">支撑</div>
        <div id="datas_team_own_aid" class="chart"></div>
        <div class="chart-title">制作人</div>
        <div id="producer_canvas" class="chart"></div>
        <div class="chart-title">编导</div>
        <div id="create_by_canvas" class="chart"></div>
    </div>
</div>

<?php echo $this->render('../default/_footer',['multimedia'=>$multimedia]); ?>
<?php 
    $datas_team = json_encode($datas_team);
    $datas_team_own_aid = json_encode($datas_team_own_aid);
    $datas_producer = json_encode($datas_producer);
    $datas_create_by = json_encode($datas_create_by);
   
    $rules = json_encode(array_values($rules));//取值保顺序
    $own_aid_rules = json_encode([0=>'部内',1=>'支撑']);
    $js = <<<JS
        var datas_team = new multimedia.BarChart(document.getElementById('datas_team'),$datas_team,$rules);
        var datas_team_own_aid = new multimedia.BarChart(document.getElementById('datas_team_own_aid'),$datas_team_own_aid,$own_aid_rules);
        var producer_chart = new multimedia.BarChart(document.getElementById('producer_canvas'),$datas_producer,$rules);
        var create_by_chart = new multimedia.BarChart(document.getElementById('create_by_canvas'),$datas_create_by,$rules);
JS;
    $this->registerJs($js);
    StatisticsAsset::register($this);
?>