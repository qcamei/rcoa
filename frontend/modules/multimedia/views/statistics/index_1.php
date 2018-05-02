<?php

use frontend\modules\multimedia\assets\StatisticsAsset;
use kartik\daterange\DateRangePicker;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
/* @var $this View */
?>
<div class="container statistics-content">
    <div class="btn-group">
        <?php echo Html::a('标准工作量', Url::to('/multimedia/statistics?type=0'), ['class'=>'btn btn-default']); ?>
        <?php echo Html::a('成品时长', Url::to('/multimedia/statistics?type=1'), ['class'=>'btn btn-default active']); ?>
    </div>
    <hr/> 
    <form class="form-horizontal">
        <input type="hidden" name="type" value="1"/>
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
    <div id="summar-content">
        <div class="summar-title">
            <span class="summar-icon"></span>总成品时长:
            <span  class="num"><?= $allWorkload ?></span>分钟
        </div>
        <br/>
        <span class="chart-title">课程中心</span>
        <div id="datas_all_type" class="chart"></div>
        <div class="row team-chart-container"></div>
    </div>
</div>

<?php echo $this->render('../default/_footer',['multimedia'=>$multimedia]); ?>
<?php 
    /**
     * 
     * @param Array $arr    [typeA:xxx,typeB:xxx]
     * @param Array $rules  规则 [0:typeA,1:typeB]
     * @return array [name:xx,value:xxx]
     */
    function convertFun($arr,$rules){
        $newArr = [];
        foreach($rules AS $name){
            $newArr [] = ['name'=>$name,'value'=>  isset($arr[$name]) ? $arr[$name] : 0];
        }
        return $newArr;
    }
    $rules = array_values($rules);//取值保顺序

    $datas_all_type = json_encode(convertFun($datas_all_type['data'],$rules));
    foreach($datas_team_type AS $team_name => $team_type_result)
        $datas_team_type[$team_name] =  convertFun($team_type_result,$rules);
    $datas_team_type = json_encode($datas_team_type);
    //转js格式
    $rules = json_encode($rules);
    $js = <<<JS
        console.log($datas_team_type);
        new multimedia.PicChart('',document.getElementById('datas_all_type'),$datas_all_type,$rules);
        var datas_team_type = $datas_team_type;
        for(var i in datas_team_type){
            new multimedia.PicChart(i,$('<div class="team-chart col-md-6"></div>').appendTo($('.team-chart-container'))[0],datas_team_type[i],$rules);
        }
JS;
    $this->registerJs($js);
    StatisticsAsset::register($this);
?>