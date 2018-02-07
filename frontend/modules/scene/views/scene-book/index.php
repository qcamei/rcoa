<?php

use common\models\scene\searchs\SceneBookSearch;
use frontend\modules\scene\assets\SceneAsset;
use kartik\widgets\DatePicker;
use wskeee\utils\DateUtil;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel SceneBookSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', '{Scene}-{Bespeak}',[
    'Scene' => Yii::t('app', 'Scene'),
    'Bespeak' => Yii::t('app', 'Bespeak'),
]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container scene-book-index scene-book">

    <?php 
        
        $reflashUrl =Yii::$app->urlManager->createAbsoluteUrl(['/scene/scene-book/index']);
        $refsite = ArrayHelper::getValue($filter, 'site_id', reset($firstSite));
        $refdate = ArrayHelper::getValue($filter, 'date', date('Y-m-d'));
        $date_switch = ArrayHelper::getValue(Yii::$app->request->queryParams, 'date_switch', 'week');
        $menuItem = [
            [
                'name'=>  Yii::t('app', 'Month'),
                'url'=> array_merge(['index'], array_merge($filter, ['date_switch' => 'month'])),
                'options' => [
                    'id' => 'month',
                    'class' => 'btn btn-default date-switch'
                ],
            ],
            [
                'name'=>  Yii::t('app', 'Week'),
                'url'=> array_merge(['index'], array_merge($filter, ['date_switch' => 'week'])),
                'options' => [
                    'id' => 'week',
                    'class' => 'btn btn-default date-switch'
                ],
            ],
        ];
        
    ?>
    
    <div class="col-xs-12 scene-book-navbar">
        <div class="col-lg-1 col-md-1 col-xs-3 btn-group">
            <?php foreach ($menuItem AS $index => $menu) {
                echo Html::a($menu['name'], $menu['url'], $menu['options']);
            } ?>
        </div>
        <div class="col-lg-7 col-md-8 col-xs-9 site-util" style="padding-left:0;">
            <?= Html::dropDownList('site_id', $refsite, 
                $sceneSite, ['id' => 'sitChange', 'class' => 'form-control'])?> 
        </div>
        <div class="col-lg-3 col-md-2 col-xs-9" style="padding:0">
            <?= DatePicker::widget([
                    'id' => 'dateChange',
                    'name' => 'date',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => date('Y/m', strtotime($refdate)),
                    'readonly' => true,
                    'options' => [
                        'placeholder' => 'Select issue date ...',
                        //'onchange'=>'dateChange($(this).val())',
                    ],
                    'pluginOptions' => [
                        'format' => 'yyyy/m',
                        'todayHighlight' => true,
                        'minViewMode' => 1,
                    ]
                ]);
            ?>
        </div>
        <div class="col-lg-1 col-md-1 col-xs-3" style="padding-right:0">
            <?= Html::a('<',Url::to(['index', 
                'site_id'=> $refsite, 
                'date' => $date_switch == 'month' ? DateUtil::getMonthSE($refdate,-1)['start'] : DateUtil::getWeekSE($refdate,-1)['start'], 
                'date_switch' => $date_switch]),['class'=>'btn btn-default']);
            ?>
            <?= Html::a('>', Url::to(['index', 
                'site_id'=> $refsite, 
                'date' => $date_switch == 'month' ? DateUtil::getMonthSE($refdate,+1)['start'] : DateUtil::getWeekSE($refdate,+1)['start'],
                'date_switch' => $date_switch]),['class'=>'btn btn-default']);
            ?>
        </div>
    </div>
    
    <div id="month" class="dataProvider">
        <?php
            if($date_switch == 'month'){
                echo $this->render('_month', [
                    'holidays' => $holidays,
                    'dataProvider' => $dataProvider, 
                    'sceneBookUser' => $sceneBookUser, 
                    'siteManage' => $siteManage
                ]);
            }
        ?>
    </div>
    <div id="week" class="dataProvider">
        <?php
            if($date_switch == 'week'){
                echo $this->render('_week', [
                    'holidays' => $holidays,
                    'dataProvider' => $dataProvider, 
                    'sceneBookUser' => $sceneBookUser, 
                    'siteManage' => $siteManage
                ]);
            }
        ?>
    </div>
    
    
    
</div>

<?php
$js = <<<JS
    var reflashUrl = "$reflashUrl",
        refsite = "{$refsite}",
        refdate = "{$refdate}",
        refswitch = "{$date_switch}";
    //选中是月 or 周
    $("a.date-switch[id=$date_switch]").addClass("active");
    $("div.dataProvider[id=$date_switch]").addClass("in");
    //场地
    $('#sitChange').change(function() {
        siteDropDownListChange($(this).val());
    });
    //日期    
    $('#dateChange').change(function() {
        dateChange($(this).val());
    });
    //生成添加场地id链接    
    function siteDropDownListChange(value) {  
        location.href = reflashUrl+'?site_id='+value+'&date='+refdate+'&date_switch='+refswitch;
    }
    //生成添加日期链接
    function dateChange(value) {
        value += '/01';
        location.href = reflashUrl+'?site_id='+refsite+'&date='+value+'&date_switch='+refswitch;
    }
    //显示节假日           
    $(".holiday").popover({
        delay: "toggle",//{ "show": 500, "hide": 100 },
        placement: $(window).width() <= 480 ? "top" : "right",
        //title: "节假日详情",
        //toggle: "popover",
        trigger: /*"click",*/"hover ",
        html: true,
    });
    //当前页面得到焦点刷新
    window.onblur = function(){
        reload();
    }
    function reload(){
        window.onfocus = function(){
            window.location.href=window.location.href; 
        }
    }
JS;
$this->registerJs($js, View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>
