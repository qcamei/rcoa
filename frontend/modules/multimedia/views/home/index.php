<?php

use common\models\multimedia\searchs\MultimediaTaskSearch;
use frontend\modules\multimedia\assets\HomeAsset;
use frontend\modules\multimedia\utils\MultimediaConvertRule;
use yii\data\ActiveDataProvider;
use yii\web\View;

/* @var $this View */
/* @var $searchModel MultimediaTaskSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/multimedia', 'Multimedia Manages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="multimedia-task-index multimedia-task multimedia-home">
    <div class="head-top"></div>
    <div class="container content">
        <div class="main-title">
            <span class="icon statistics-disable-icon"></span>本月已完成标准工作量( 分钟 )
        </div>
        <div class="title">
            换算规则：
            <span>
                <?php
                    foreach($rules as $id => $name)
                        echo "<span class='rule-name'>$name</span><span class='rule-proportion'>(1 : ".
                            MultimediaConvertRule::getInstance()->getRuleProportion($id).")</span>";
                ?>
            </span>
        </div>
        <div class="title">
            制作人：
        </div>
        <div id="producer_canvas" class="chart"></div>
        <div class="title">
            编导：
        </div>
        <div id="create_by_canvas" class="chart"></div>
    </div>
</div>

<?php echo $this->render('../default/_footer',['multimedia'=>$multimedia]); ?>

<?php 
    $datas_producer = json_encode($datas_producer);
    $datas_create_by = json_encode($datas_create_by);
    $rules = json_encode($rules);
    $js = <<<JS
        var producer_chart = new multimedia.BarChart(document.getElementById('producer_canvas'),$datas_producer,$rules);
        var create_by_chart = new multimedia.BarChart(document.getElementById('create_by_canvas'),$datas_create_by,$rules);
JS;
    $this->registerJs($js);
    HomeAsset::register($this);
?>