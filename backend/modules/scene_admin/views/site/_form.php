<?php

use common\models\scene\SceneSite;
use common\widgets\ueditor\UeditorAsset;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model SceneSite */
/* @var $form ActiveForm */
//var_dump($model->getCityList(28240));exit;
?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=r2OdCIhHY8ZEY4fZQG7DGjl1nAIVoH0a"></script>
        
<div class="scene-site-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'scene-site-form',
        ],
    ]); ?>
    <div class="col-lg-12 col-md-12" style="padding: 0px">
        <div class="col-lg-7 col-md-7" style="padding: 0px 15px 0 0">
            <?= $form->field($model, 'op_type')->dropDownList(SceneSite::$TYPES, ['prompt' => '请选择...'])?>
            <?= $form->field($model, 'area')->widget(Select2::className(),[
                'data' => $area,
                'options' => ['placeholder' => '请选择...'],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 10
                ],
            ]);?>
            
            <div class="col-lg-12 col-md-12" style="padding: 0px">
                <div class="col-lg-3 col-md-3" style="padding: 0px 30px 0 0">
                    <?= $form->field($model, 'province')->dropDownList($model->getCityList(0),[
                        'prompt'=>'--选择省--',
                        'onchange'=>'  
                            $(".form-group#scenesite-province").hide();
                            $.post("'.Yii::$app->urlManager->createUrl('scene_admin/site/search-address').'?level=1&parent_id="+$(this).val(),function(data){  
                                $("select#scenesite-city").html(data);  
                        });',  
                    ]) ?>
                </div>
                <div class="col-lg-3 col-md-3">
                    <?= $form->field($model, 'city')->dropDownList($model->getCityList($model->province),[
                        'prompt'=>'--选择市--',
                        'onchange'=>'
                            $(".form-group#scenesite-city").show();
                            $.post("'.Yii::$app->urlManager->createUrl('scene_admin/site/search-address').'?level=2&parent_id="+$(this).val(),function(data){
                                $("select#scenesite-district").html(data);
                            });',
                    ]) ?>
                </div>
                <div class="col-lg-3 col-md-3">
                    <?= $form->field($model, 'district')->dropDownList($model->getCityList($model->city),[
                        'prompt'=>'--选择区--',
                        'onchange'=>'
                            $(".form-group#scenesite-district").show();
                            $.post("'.Yii::$app->urlManager->createUrl('scene_admin/site/search-address').'?level=3&parent_id="+$(this).val(),function(data){
                                $("select#scenesite-twon").html(data);
                            });',
                    ]) ?>
                </div>
                <div class="col-lg-3 col-md-3">
                    <?= $form->field($model, 'twon')->dropDownList($model->getCityList($model->district),[
                        'prompt'=>'--选择镇--',
                    ]) ?>
                </div>
            </div>
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            
            <?= $form->field($model, 'contact')->widget(Select2::classname(), [
                'data' => $model->getUsers(), 'options' => ['placeholder' => '请选择...']
            ]) ?>
            
            <?= $form->field($model, 'manager_id')->widget(Select2::classname(), [
                'data' => $model->getUsers(), 'options' => ['placeholder' => '请选择...']
            ]) ?>

            <?= $form->field($model, 'des')->textarea(['rows' => 6]) ?>

        </div>
        <div class="col-lg-5 col-md-5">
            <?= $form->field($model, 'content_type')->checkboxList(SceneSite::$CONTENT_TYPES, [
                'value' => explode(',', $model->content_type)
            ]) ?>

            <?= $form->field($model, 'price')->textInput(['maxlength' => true, 'placeholder' => '元/小时',]) ?>

            <?= $form->field($model, 'is_publish')->widget(SwitchInput::classname(), [
                'pluginOptions' => [
                    'onText' => Yii::t('app', 'Y'),
                    'offText' => Yii::t('app', 'N'),
                ]
            ]);?>

            <?= $form->field($model, 'sort_order')->textInput() ?>

            <?= $form->field($model, 'img_path')->widget(FileInput::classname(), [
                'options' => [
                    'accept' => 'image/*',
                    'multiple' => false,
                ],
                'pluginOptions' => [
                    'resizeImages' => true,
                    'showCaption' => false,
                    'showRemove' => false,
                    'showUpload' => false,
                    'browseClass' => 'btn btn-primary btn-block',
                    'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                    'browseLabel' => '选择上传主图片...',
                    'initialPreview' => [
                        $model->isNewRecord ?
                                Html::img(Yii::getAlias('@filedata') . '/filedata/scene', ['class' => 'file-preview-image', 'width' => '213']) :
                                Html::img(WEB_ROOT . $model->img_path, ['class' => 'file-preview-image', 'width' => '213']),
                    ],
                    'overwriteInitial' => true,
                ],
            ]);?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12" style="padding: 0px">
        <h5 style="font-weight: bold">位置</h5>
        <?= Html::label('','',[
            'id' => 'map',
            'style' => 'width:100%; height:500px;',
        ])?>
        <?= Html::activeHiddenInput($model, 'location') ?>
        
        <?= $form->field($model, 'content')->textarea([
            'id' => 'container', 
            'type' => 'text/plain', 
            'style' => 'width:100%; height:400px;',
            'placeholder' => '文章内容...'
        ])?>
    </div>
    <?php //$form->field($model, 'country')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php

$js =
<<<JS
    /** 富文本编辑器 */
    $('#container').removeClass('form-control');
    var ue = UE.getEditor('container');
        
    /** 百度地图设置 */    
    var map = new BMap.Map("map");                      // 创建地图实例 
    var point = new BMap.Point(113.2759952545166,23.117055306224895);   //地图初始位置
    map.centerAndZoom(point, 12);                       // 初始化地图，设置中心点坐标和地图级别

    var myGeo = new BMap.Geocoder();                    // 创建地址解析器实例
    // 当地址输入框失去焦点时出发事件
    $('#scenesite-address').blur(function() {
        // 将地址解析结果显示在地图上,并调整地图视野
        myGeo.getPoint($('#scenesite-address').val(), function(point){
            if (point) {
                $('#scenesite-location').val(point.lng + " " + point.lat);
                map.centerAndZoom(point, 16);
                var marker = new BMap.Marker(point);    // 创建标注
                map.addOverlay(marker);                 // 将标注添加到地图中
                marker.addEventListener("dragend",onMarkerDragend);
                marker.enableDragging();                //设置标注是否可以移动
                function onMarkerDragend(e){
                    //获取marker的位置
                    $('#scenesite-location').val(e.point.lng + " " + e.point.lat);
                }
            }else{
                alert("您输入的详细地址没有解析到结果!");
            }
        });
    });
        
    var top_left_navigation = new BMap.NavigationControl(); //左上角，添加默认缩放平移控件
    map.addControl(top_left_navigation);
     
    var size = new BMap.Size(10, 20);                       //右上角，城市选择控件
    map.addControl(new BMap.CityListControl({
        anchor: BMAP_ANCHOR_TOP_RIGHT,
        offset: size,
    }));
    map.addEventListener("tilesloaded",function(){
        map.removeEventListener("tilesloaded",arguments.callee);
        $('#scenesite-address').trigger('blur');
    });
        
//    map.enableScrollWheelZoom(true);                      //开启鼠标滚轮缩放
JS;
    $this->registerJs($js,  View::POS_READY); 
?>

<?php
    UeditorAsset::register($this);
?>