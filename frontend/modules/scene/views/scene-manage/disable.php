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
/* @var $searchModel SceneSiteDisableSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', '{Scene}{Disabled}',[
    'Scene' => Yii::t('app', 'Scene'),
    'Disabled' => Yii::t('app', 'Disabled'),
]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container scene-book-index scene-book">

    <?php 
        
        $reflashUrl =Yii::$app->urlManager->createAbsoluteUrl(['/scene/scene-manage/disable']);
        $refsite = ArrayHelper::getValue($filter, 'site_id', reset($firstSite));
        $refdate = ArrayHelper::getValue($filter, 'date', date('Y-m-d'));
        $date_switch = ArrayHelper::getValue(Yii::$app->request->queryParams, 'date_switch', 'month');
        $menuItem = [
            [
                'name'=>  Yii::t('app', 'Month'),
                'url'=> array_merge(['disable'], array_merge($filter, ['date_switch' => 'month'])),
                'options' => [
                    'id' => 'month',
                    'class' => 'btn btn-default date-switch'
                ],
            ],
            [
                'name'=>  Yii::t('app', 'Week'),
                'url'=> array_merge(['disable'], array_merge($filter, ['date_switch' => 'week'])),
                'options' => [
                    'id' => 'week',
                    'class' => 'btn btn-default date-switch'
                ],
            ],
        ];
        
    ?>
    
    <div class="col-xs-12 scene-book-navbar">
        <div class="col-lg-1 col-md-1 col-xs-4 btn-group">
            <?php foreach ($menuItem AS $index => $menu) {
                echo Html::a($menu['name'], $menu['url'], $menu['options']);
            } ?>
        </div>
        <div class="col-lg-8 col-md-8 col-xs-8" style="padding:0">
            <?= Html::dropDownList('site_id', $refsite, 
                $sceneSite, ['id' => 'sitChange', 'class' => 'form-control', 'prompt' => '请选择...'])?> 
        </div>
        <div class="col-lg-2 col-md-2 col-xs-5" style="padding:0">
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
        <div class="col-lg-1 col-md-1 col-xs-4" style="padding-right:0">
            <?= Html::a('<',Url::to(['disable', 
                'site_id'=> $refsite, 
                'date' => $date_switch == 'month' ? DateUtil::getMonthSE($refdate,-1)['start'] : DateUtil::getWeekSE($refdate,-1)['start'], 
                'date_switch' => $date_switch]),['class'=>'btn btn-default']);
            ?>
            <?= Html::a('>', Url::to(['disable', 
                'site_id'=> $refsite, 
                'date' => $date_switch == 'month' ? DateUtil::getMonthSE($refdate,+1)['start'] : DateUtil::getWeekSE($refdate,+1)['start'],
                'date_switch' => $date_switch]),['class'=>'btn btn-default']);
            ?>
        </div>
    </div>
    
    <div id="month" class="dataProvider">
        <?= $this->render('_month', ['dataProvider' => $dataProvider, 'sceneBookUser' => $sceneBookUser]) ?>
    </div>
    <div id="week" class="dataProvider">
        <?= $this->render('_week', ['dataProvider' => $dataProvider, 'sceneBookUser' => $sceneBookUser]) ?>
    </div>
    
    
    
</div>

<?php

$js = <<<JS
    var reflashUrl = "$reflashUrl",
        refsite = "{$refsite}",
        refdate = "{$refdate}",
        refswitch = "{$date_switch}";
    
        
    $("a.date-switch[id=$date_switch]").addClass("active");
    $("div.dataProvider[id=$date_switch]").addClass("in");
    
    $('#sitChange').change(function() {
        siteDropDownListChange($(this).val());
    });
        
    $('#dateChange').change(function() {
        dateChange($(this).val());
    });
        
    function siteDropDownListChange(value) {  
        location.href = reflashUrl+'?site_id='+value+'&date='+refdate+'&date_switch='+refswitch;
    }
        
    function dateChange(value) {
        value += '/01';
        location.href = reflashUrl+'?site_id='+refsite+'&date='+value+'&date_switch='+refswitch;
    }
JS;
$this->registerJs($js, View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>
