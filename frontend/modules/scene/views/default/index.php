<?php

use common\models\scene\SceneSite;
use frontend\modules\scene\assets\SceneAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', '{Scene}-{Homepage}',[
    'Scene' => Yii::t('app', 'Scene'),
    'Homepage' => Yii::t('app', 'Homepage'),
]);

?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=r2OdCIhHY8ZEY4fZQG7DGjl1nAIVoH0a"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/TextIconOverlay/1.2/src/TextIconOverlay_min.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/MarkerClusterer/1.2/src/MarkerClusterer_min.js"></script>

<div class="scene-default-index container">
    <!--地图-->
    <div class="map col-lg-8">
        <i class="fa fa-pie-chart" aria-hidden="true"></i><span class="title">&nbsp;全国分布图</span>
        <div class="map-content" id="allmap">
            
        </div>
    </div>
    <!--场地列表-->
    <div class="select col-lg-4">
        <div class="crumb">
            <ul class="crumb-nav">
                <li><i class="fa fa-list-ul" aria-hidden="true"></i><span class="title">&nbsp;选择场地</span></li>
                <?php foreach ($filterItem as $filter_key => $item): ?>
                    <li>
                        <i class="arrow">&gt;</i>
                        <?= Html::a("<b>{$filter_key}:</b><em>{$item['filter_value']}</em><i>×</i>", [$item['url'],'#'=>'scroll']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!--过滤条件-->
        <div class="select-choice">
            <ul class="attr-nav">
                <!--性质-->
                <li>
                    <span class="attr-key"><?= Yii::t('app', 'Nature') ?>：</span>
                    <ul class="attr-value">
                        <?php foreach (SceneSite::$TYPES as $key => $nature): ?>
                        <li>
                            <?= Html::a($nature,Url::to(array_merge(['index'],
                                    array_merge($filter, ['op_type' => $key, 'page' => 1, '#' => 'scroll'])))) 
                            ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <!--区域-->
                <li>
                    <span class="attr-key"><?= Yii::t('app', 'Area') ?>：</span>
                    <ul class="attr-value">
                        <?php foreach ($area as $area_name): ?>
                        <li>
                            <?= Html::a($area_name, Url::to(array_merge(['index'], 
                                    array_merge($filter, ['area' => $area_name, 'page' => 1, '#' => 'scroll'])))) 
                            ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <!--内容类型-->
                <li>
                    <span class="attr-key"><?= Yii::t('app', '{Content}{Type}',[
                        'Content' => Yii::t('app', 'Content'), 'Type' => Yii::t('app', 'Type')]) ?>：</span>
                    <ul class="attr-value">
                        <?php foreach (SceneSite::$CONTENT_TYPES as $content_type): ?>
                        <li>
                            <?= Html::a($content_type, Url::to(array_merge(['index'],
                                    array_merge($filter, ['content_type' => $content_type, 'page' => 1, '#' => 'scroll']))))
                            ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </div>
        <!--过滤器-->
        <?= $this->render('/layouts/filter', ['filter' => $filter]) ?>
        <!--场地列表-->
        <div class="select-content">
            <?php foreach ($sceneItem['query'] as $index => $scenes):?>
            <div class="address">
                <div class="address-content">
                    <a href="<?= Url::to(['scene-book/index', 'site_id' => $scenes['id'], 'date' => date('Y-m-d'), 'date_switch' => 'week'])?>">
                        <div class="address-left">
                            <div class="scene-img"><img src="<?= $scenes['img_path']?>"></div>
                            <div class="address-mark bg-color <?= ($scenes['op_type'] == 1) ? 'add-red' : 'add-blue'?>">
                                                        <?= ($scenes['op_type'] == 1) ? '自营' : '合作'?></div>
                        </div>
                        <div class="address-right">
                            <div class="address-name"><?= $scenes['name']?></div>
                            <div class="address-area">区域：<span><?= $scenes['area']?></span>&nbsp;
                                                        <font class="font">(<?= $scenes['address']?>)</font></div>
                            <div class="address-type">内容：<span><?= $scenes['content_type']?></span></div>
                            <div class="address-price">价格：<span>￥<?= $scenes['price']?>/小时</span></div>
                        </div>
                    </a>
                </div>
                <a class="address-info" href="<?= Url::to(['scene-manage/view', 'id' => $scenes['id']])?>" title="<?= $scenes['address']?>">
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                </a>
            </div>
            <?php endforeach;?>
        </div>
        <!--分页-->
        <?= $this->render('/layouts/page', ['filter' => $filter, 'pages' => $sceneItem['pages']]) ?>
    </div>
</div>
<?php

$map = [];
foreach ($sceneItem['data'] as $key => $sceneInfo){
    $map_x = $sceneInfo['X(location)'];                 //经度
    $map_y = $sceneInfo['Y(location)'];                 //纬度
    $map_address = $sceneInfo['address'];               //地址
    $map[] = [
        'x' => $map_x,
        'y' => $map_y,
        'ads' => $map_address,
    ];
}   
$maps = json_encode($map); 

$js = <<<JS
       
    // 百度地图API功能	
    map = new BMap.Map("allmap");
    var point_first = new BMap.Point(105.880746, 35.95393);   //地图初始位置
    setTimeout(function(){
        map.centerAndZoom(point_first, 5);
    },2000);
    map.centerAndZoom(point_first, 4);
    
    var data_info = $maps;
    var markers = [];
    var point = null;
    for (var i in data_info) {
        var point = new BMap.Point(data_info[i].x, data_info[i].y);
        var marker = new BMap.Marker(point);
        var content = data_info[i].ads;
        addClickHandler(content, marker); //添加点击事件
        markers.push(marker);
    };
    //最简单的用法，生成一个marker数组，然后调用markerClusterer类即可。
    var markerClusterer = new BMapLib.MarkerClusterer(map, {
        markers:markers,
    });

    var opts = {
        width : 200,            // 信息窗口宽度
        height: 60,             // 信息窗口高度
        title : "地址：" ,       // 信息窗口标题
        enableMessage:true      //设置允许信息窗发送短息
    };
    function addClickHandler(content,marker){       //点击事件
        marker.addEventListener("click",function(e){
                openInfo(content,e)}
        );
    };
    function openInfo(content,e){
        var p = e.target;
        var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
        var infoWindow = new BMap.InfoWindow(content,opts); // 创建信息窗口对象 
        map.openInfoWindow(infoWindow,point);               //开启信息窗口
    }; 
        
    var top_left_navigation = new BMap.NavigationControl(); //左上角，添加默认缩放平移控件
    map.addControl(top_left_navigation);
    map.enableScrollWheelZoom(true);                        //开启鼠标滚轮缩放
JS;
$this->registerJs($js, View::POS_READY);
?>
<?php
    SceneAsset::register($this);
