<?php

use frontend\modules\multimedia\assets\StatisticsAsset;
use kartik\widgets\DatePicker;
use yii\web\View;
/* @var $this View */
?>
<div class="container statistics-content">
    <form class="form-horizontal">
        <div class="form-group">
          <label for="dateRange" class="col-sm-2 control-label"><?php echo Yii::t('rcoa/multimedia', 'Statistics-Year') ?></label>
          <div class="col-sm-10">
              <?php
              echo DatePicker::widget([
                        'id' => 'date',
                        'name' => 'year',
                        'type' => DatePicker::TYPE_COMPONENT_APPEND,
                        'value' => $year,
                        'readonly' => true,
                        'options' => [
                            'placeholder' => 'Select issue date ...',
                            //'onchange'=>'dateChange($(this).val())',
                            ],
                        'pluginOptions' => [
                            'format' => 'yyyy',
                            'todayHighlight' => true,
                            'minViewMode' => 2,
                        ]
                    ]);
              ?>
          </div>
        </div>
        
        
        <div class="form-group">
          <label for="item_type_id" class="col-sm-2 control-label"><?php echo Yii::t('rcoa/multimedia', 'Statistics-Month') ?></label>
          <div class="col-sm-10 months">
              <?php
                  for ($i = 0, $len = 12; $i < $len; $i++) {
                      echo '<input id="m'.$i.'" class="month" type="checkbox" name="months[]" '.(isset($months[$i+1])?'checked':"").' value="'.($i+1).'">'
                              . '<label for="m'.$i.'">'.($i+1).'月</label>'
                              . '</input>';
                  }
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
        <br/>
        <span class="chart-title">团队</span>
        <div id="datas_team" class="chart"></div>
        <span class="chart-title">支撑</span>
        <div id="datas_team_own_aid" class="chart"></div>
        <span class="chart-title">编导</span>
        <div id="producer_canvas" class="chart"></div>
        <span class="chart-title">制作人</span>
        <div id="create_by_canvas" class="chart"></div>
    </div>
</div>

<?php echo $this->render('../default/_footer',['multimedia'=>$multimedia]); ?>
<?php 

    $datas_team = json_encode($datas_team);
    $datas_team_own_aid = json_encode($datas_team_own_aid);
    $datas_producer = json_encode($datas_producer);
    $datas_create_by = json_encode($datas_create_by);
   
    $rules = json_encode($rules);
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