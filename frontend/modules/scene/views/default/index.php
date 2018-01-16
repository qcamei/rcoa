<?php

use common\models\scene\SceneSite;
use frontend\modules\scene\assets\SceneAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', 'Scene');
$filter = Yii::$app->request->queryParams;

?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=r2OdCIhHY8ZEY4fZQG7DGjl1nAIVoH0a"></script>
<!--<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>-->

<div class="scene-default-index">
    <!--地图-->
    <div class="map col-lg-8">
        <span>全国分布图</span>
        <div class="map-content" id="allmap">
            
        </div>
    </div>
    <!--场地列表-->
    <div class="select col-lg-4">
        <div class="crumb">
            <ul class="crumb-nav">
                <li><span>场地选择</span></li>
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
            <?php foreach ($sceneItem['query'] as $index => $scnes):?>
            <div class="address">
                <div class="address-top">
                    <span class="address-name"><?= $scnes['name']?></span>
                    <span class="btn btn-default btn-sm" style="float: right">预约</span>
                </div>
                <div class="address-content">
                    <a href="<?= Url::to(['view', 'id' => $scnes['id']]) ?>" class="address-img" title="<?= $scnes['address']?>">
                        <img src="<?= $scnes['img_path']?>">
                    </a>
                    <div class="address-right">
                        <div class="address-nature bg-color <?= ($scnes['op_type'] == 1) ? 'add-red' : 'add-blue'?>">
                                                    <?= ($scnes['op_type'] == 1) ? '自营' : '合作'?></div>
                        <div class="address-area">区域：<span><?= $scnes['area']?></span>&nbsp;
                                                    <font class="font">(<?= $scnes['address']?>)</font></div>
                        <div class="address-type">内容类型：<span><?= $scnes['content_type']?></span></div>
                        <div class="address-price">价格：<span>￥<?= $scnes['price']?>/小时</span> （4小时起）</div>
                    </div>
                </div>
            </div>
            <?php endforeach;?>
        </div>
        <!--分页-->
        <?= $this->render('/layouts/page', ['filter' => $filter, 'pages' => $sceneItem['pages']]) ?>
    </div>
</div>
<?php

    foreach ($sceneItem['query'] as $key => $sceneInfo){
        preg_match_all("/\((.*)\)/", $sceneInfo['AsText(location)'], $map_xy);        //获取括号里面的内容
        $map_all = explode(' ', $map_xy['1']['0']);         //拆分转为数组
        $map_x = $map_all['0'];                             //经度
        $map_y = $map_all['1'];                             //纬度
        $map_address = $sceneInfo['address'];               //地址
//        var_dump($map_x,$map_y,$map_address);exit;
    }

$js = <<<JS
        
    // 百度地图API功能	
    map = new BMap.Map("allmap");
    map.centerAndZoom(new BMap.Point(105.880746, 31.95393), 5);
      
//    var MAX = 10;
//    var markers = [];
//    var pt = null;
//    var i = 0;
//    for ( ; i < MAX; i++) {
//        pt = new BMap.Point(Math.random() * 40 + 85, Math.random() * 30 + 21);
//        markers.push(new BMap.Marker(pt));
//    }
    var data_info = [[$map_x,$map_y, "地址：$map_address"],
                     [116.406605,39.921585, "地址：北京市东城区东华门大街"],
                     [116.412222,39.912345, "地址：北京市东城区正义路甲5号"]
                   ];
    var opts = {
            width : 200,            // 信息窗口宽度
            height: 60,             // 信息窗口高度
            title : "地点信息" ,     // 信息窗口标题
            enableMessage:true      //设置允许信息窗发送短息
       };
    for(var i=0;i<data_info.length;i++){
        var marker = new BMap.Marker(new BMap.Point(data_info[i][0],data_info[i][1]));  // 创建标注
        var content = data_info[i][2];
        map.addOverlay(marker);               // 将标注添加到地图中
        addClickHandler(content,marker);
    };
    function addClickHandler(content,marker){
        marker.addEventListener("click",function(e){
                openInfo(content,e)}
        );
    };
    function openInfo(content,e){
        var p = e.target;
        var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
        var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象 
        map.openInfoWindow(infoWindow,point); //开启信息窗口
    }; 
    var top_left_navigation = new BMap.NavigationControl(); //左上角，添加默认缩放平移控件
    map.addControl(top_left_navigation);
    map.enableScrollWheelZoom(true);                      //开启鼠标滚轮缩放
JS;
$this->registerJs($js, View::POS_READY);
?>
<?php
    SceneAsset::register($this);
