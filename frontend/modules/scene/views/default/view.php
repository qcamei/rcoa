<?php

use frontend\modules\scene\assets\SceneAsset;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', '{Scene}{Detail}', [
            'Scene' => Yii::t('app', 'Scene'),
            'Detail' => Yii::t('app', 'Detail'),
        ]);

?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=r2OdCIhHY8ZEY4fZQG7DGjl1nAIVoH0a"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>
<link rel="stylesheet" href="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.css" />

<div class="scene-default-view">
    <div class="introduce col-lg-12">
        <div class="scene-img col-lg-7">
            <img src="<?= $sceneData['img_path'] ?>">
        </div>
        <div class="scene-info col-lg-5">
            <div class="info-content">
                <div class="scene-name"><?= $sceneData['name'] ?></div>
                <div class="scene-nature"><span class="span">性质：</span>
                    <div class="bg-color <?= ($sceneData['op_type'] == 1) ? 'add-red' : 'add-blue' ?>">
                        <?= ($sceneData['op_type'] == 1) ? '自营' : '合作' ?>
                    </div>
                </div>
                <div class="scene-area"><span>区域：</span><?= $sceneData['area'] ?></div>
                <div class="scene-type"><span>内容：</span><?= $sceneData['content_type'] ?></div>
                <div class="scene-price"><span>价格：</span>￥<?= $sceneData['price'] ?>/小时</div>
                <div class="scene-num"><span>总预约：</span><?= $registerNum ?> 次</div>
                <div class="scene-contact"><span>联系人：</span><?= $sceneData['contact'] ?></div>
                <div class="scene-address"><span>地址：</span><?= $sceneData['address'] ?></div>
                <div class="scene-des"><span>简介：</span><font><?= $sceneData['des'] ?></font></div>
            </div>
        </div>
    </div>

    <div class="scene-details col-lg-12">
        <div class="tablist">
            <ul>
                <li class="active">
                    <a href="#details" onclick="tabClick($(this));return false;">
                        <i class="details"></i><em>详情</em>
                    </a>
                </li>
                <li class="none">
                    <a href="#scene-map" onclick="tabClick($(this));return false;">
                        <i class="scene-map"></i><em>位置</em>
                    </a>
                </li>
            </ul>
        </div>
        <div class="tabcontent">
            <div id="details" class="tabpane show">
                <div class="resource"><?= $sceneData['content'] ?></div>
            </div>
            <div id="scene-map" class="tabpane">
                <div id="allmap" class="allmap"></div>
            </div>
        </div>
    </div>

</div>
<?php
$map = $sceneData['AsText(location)'];
preg_match_all("/\((.*)\)/", $map, $map_xy);        //获取括号里面的内容
$map_all = explode(' ', $map_xy['1']['0']);         //拆分转为数组
$map_x = $map_all['0'];                             //经度
$map_y = $map_all['1'];                             //纬度

$map_img = $sceneData['img_path'];                  //图片路径
$map_contact = $sceneData['contact'];               //联系人
$map_address = $sceneData['address'];               //地址
$map_des = $sceneData['des'];                       //简介

$js = <<<JS
        
    //单击切换标签
    window.tabClick = function (elem) {
        $(elem).parent().siblings("li").removeClass("active");
        $(elem).parent("li").addClass("active");
        var idName = $(elem).attr("href");
        $(idName).siblings("div").animate({opacity: 0}, 300).removeClass("show");
        $(idName).animate({opacity: 1}, 250).addClass("show");
    };
        
    /** 百度地图设置 */   
    var map = new BMap.Map("allmap");
    var point = new BMap.Point($map_x,$map_y);      //地图初始位置
    map.centerAndZoom(point, 16);                   //初始化地图，设置中心点坐标和地图级别
    
    var content = '<div style="margin:0;line-height:20px;padding:2px;">' +
                    '<img src="$map_img" alt="" style="float:right;zoom:1;overflow:hidden;width:100px;height:100px;margin-left:3px;"/>' +
                    '地址：$map_address<br/>联系人：$map_contact<br/>简介：$map_des' +
                '</div>';
    //创建检索信息窗口对象
    var searchInfoWindow = null;
    searchInfoWindow = new BMapLib.SearchInfoWindow(map, content, {
                    title  : "地址信息",         //标题
                    width  : 300,               //宽度
                    height : 105,               //高度
                    panel  : "panel",           //检索结果面板
                    enableAutoPan : true,       //自动平移
                    searchTypes   :[
                            BMAPLIB_TAB_SEARCH,   //周边检索
                            BMAPLIB_TAB_TO_HERE,  //到这里去
                            BMAPLIB_TAB_FROM_HERE //从这里出发
                    ]
            });
    var marker = new BMap.Marker(point);            //创建标注
    marker.addEventListener("click", function(e){
        searchInfoWindow.open(marker);
    });
    map.addOverlay(marker);                         //将标注添加到地图中
    map.addEventListener("tilesloaded",function aa(){  //增加地图加载完成监听事件
        map.panTo(point);                           //将地图中心点移动到定位的这个点位置
    });    
    var top_left_navigation = new BMap.NavigationControl(); //左上角，添加默认缩放平移控件
    map.addControl(top_left_navigation);
JS;
$this->registerJs($js, View::POS_READY);
?>
<?php
SceneAsset::register($this);
