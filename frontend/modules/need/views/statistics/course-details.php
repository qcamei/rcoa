<?php

use common\widgets\charts\ChartAsset;
use frontend\modules\need\assets\ModuleAssets;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', '{Statistics}-{Course Details}',[
    'Statistics' => Yii::t('app', 'Statistics'),
    'Course Details' => Yii::t('app', 'Course Details'),
]);

$radioType = [
    '0' => '按内容',
    '1' => '按人',
]
        
?>

<div class="statistics statistics-course-details">
    <form class="form-horizontal">
        <!--行业等过滤条件-->
        <div class="form-group">
            <!--行业 business-->
            <div class="filter col-sm-4">
                <label for="business" class="col-sm-2 control-label filter-title"><?php echo Yii::t('app', 'Business') ?></label>
                <div class="col-sm-9">
                      <?php 
                      echo Select2::widget([
                          'value'=> $business,
                          'name' => 'business',
                          'data' => $businesss,
                          'options' => [
                              'placeholder' => Yii::t('app', 'All'),
                          ],
                          'pluginOptions' => [
                              'allowClear' => true
                          ],
                      ]);?>
                </div>
            </div>
            <!--层次类型 layer id-->
            <div class="filter col-sm-4">
            <label for="layer" class="col-sm-3 control-label filter-title"><?php echo Yii::t('app', 'Layer ID') ?></label>
                <div class="col-sm-9">
                      <?php 
                      echo Select2::widget([
                          'value'=> $layer,
                          'name' => 'layer',
                          'id' => 'layer',
                          'data' => $layers,
                          'options' => [
                              'placeholder' => Yii::t('app', 'All'),
                              'onchange'=>'wx_one(this)',
                          ],
                          'pluginOptions' => [
                              'allowClear' => true
                          ],
                      ]);?>
                </div>
            </div>
            <!--专业工种 profession id-->
            <div class="filter col-sm-4">
                <label for="profession" class="col-sm-3 control-label filter-title"><?php echo Yii::t('app', 'Profession ID') ?></label>
                <div class="col-sm-9">
                      <?php 
                      echo Select2::widget([
                          'value'=> $profession,
                          'data' => $professions,
                          'name' => 'profession',
                          'id' => 'profession',
                          'options' => [
                              'placeholder' => Yii::t('app', 'All'),
                              'onchange'=>'wx_two(this)',
                          ],
                          'pluginOptions' => [
                              'allowClear' => true
                          ],
                      ]);?>
                </div>
            </div>
        </div>
        <!--课程 course-->
        <div class="form-group">
            <div class="filter col-sm-4">
                <label for="course" class="col-sm-3 control-label filter-title"><?php echo Yii::t('app', 'Courses') ?></label>
                <div class="col-sm-9">
                      <?php 
                      echo Select2::widget([
                          'value'=> $course,
                          'data' => $courses,
                          'name' => 'course',
                          'id' => 'course',
                          'options' => [
                              'placeholder' => Yii::t('app', 'All'),
                          ],
                          'pluginOptions' => [
                              'allowClear' => true
                          ],
                      ]);?>
                </div>
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
            <div id="workitemCanvas" class="chart"></div>
        <?php elseif ($type == 1): ?>
            <div id="presonalCanvas" class="chart"></div>
        <?php endif;?>
    </div>
</div>
<script type="text/javascript">
    //动态获取专业/工种
    function wx_one(e,select){
	$("#profession").html("");
	$("#select2-profession-container").html("全部");
	$.post("/framework/api/search?id="+$(e).val(),function(data)
        {
            var selectedName = "";
            $('<option/>').appendTo($("#profession"));
            $.each(data['data'],function()
            {
                $('<option>').val(this['id']).text(this['name']).appendTo($("#profession"));
                if(select && select === this['id'])
                    selectedName = this['name'];
            });
            if(select)
            {
                $("#profession").val(select);
                $("#select2-profession-container").html(selectedName);
            }
	});
    };
    //动态获取课程
    function wx_two(e,select){
	$("#course").html("");
	$("#select2-course-container").html("全部");
	$.post("/framework/api/search?id="+$(e).val(),function(data)
        {
            var selectedName = "";
            $('<option/>').appendTo($("#course"));
            $.each(data['data'],function()
            {
                $('<option>').val(this['id']).text(this['name']).appendTo($("#course"));
                if(select && select === this['id'])
                    selectedName = this['name'];
            });
            if(select)
            {
                $("#course").val(select);
                $("#select2-course-container").html(selectedName);
            }
	});
    }
</script>
<?php
$workitems = json_encode($workitems);   //工作项
$items = json_encode($items);      
$presonal = json_encode($presonal);     //人

$js = <<<JS
        if($type === 0){
            var workitemCanvas = new ccoacharts.MultiBarChart({title:"",itemLabelFormatter:'{c}元'},document.getElementById('workitemCanvas'),$workitems,$items);
        }else if($type === 1){
            var presonalChart = new ccoacharts.BarChart({title:"",itemLabelFormatter:'{c} 元'},document.getElementById('presonalCanvas'),$presonal);
        }
        
JS;

    $this->registerJs($js, View::POS_READY);
    ChartAsset::register($this);
    ModuleAssets::register($this);
?>
