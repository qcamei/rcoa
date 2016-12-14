<?php
    use frontend\modules\demand\assets\PageListAssets;
    use yii\web\View;

    /* @var $this View */
?>

<div class="container demand-default-index">
    <h1>主页</h1>
    <div style="height: 30px">
        <span id="pl-comeback">返回</span>
    </div>
    
    <div style="height: 600px">
        <div id="e-pl" class="e-pl"></div>
    </div>
</div>
<?php 
    $data = [
        [
            'id'=>'111',
            'name'=>'蓝箱类',
            'type'=>'dir',
            'des'=>'蓝箱类场景',
            'children'=>[
                [
                    'id'=>'111_1',
                    'name'=>'蓝箱子类',
                    'type'=>'dir',
                    'des'=>'蓝箱子类场景',
                    'children'=>[
                        [
                            'id'=>'111_1_1',
                            'name'=>'蓝箱A',
                            'type'=>'content',
                            'des'=>'蓝箱',
                            'price'=>'￥2,142.00'
                        ],
                        [
                            'id'=>'111_1_2',
                            'name'=>'蓝箱B',
                            'type'=>'content',
                            'des'=>'蓝箱',
                            'price'=>'￥2,142.00'
                        ]    
                    ]
                ],
                [
                    'id'=>'111_2',
                    'name'=>'蓝箱B',
                    'type'=>'content',
                    'des'=>'蓝箱',
                    'price'=>'￥2,142.00'
                ]    
            ]
        ],
        [
            'id'=>'112',
            'name'=>'动画类',
            'type'=>'dir',
            'des'=>'蓝箱类场景',
            'children'=>[
                [
                    'id'=>'112_1',
                    'name'=>'动画A',
                    'type'=>'content',
                    'des'=>'动画',
                    'price'=>'￥2,142.00'
                ],
                [
                    'id'=>'112_2',
                    'name'=>'动画B',
                    'type'=>'content',
                    'des'=>'动画',
                    'price'=>'￥2,142.00'
                ]    
            ]
        ],
        [
            'id'=>'113',
            'name'=>'动画C',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ],
        [
            'id'=>'114',
            'name'=>'动画D',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ],
        [
            'id'=>'115',
            'name'=>'动画C',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ],
        [
            'id'=>'116',
            'name'=>'动画D',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ],
        [
            'id'=>'117',
            'name'=>'动画C',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ],
        [
            'id'=>'118',
            'name'=>'动画D',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ],
        [
            'id'=>'119',
            'name'=>'动画C',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ],
        [
            'id'=>'120',
            'name'=>'动画D',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ],
        [
            'id'=>'121',
            'name'=>'动画C',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ],
        [
            'id'=>'122',
            'name'=>'动画D',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ],
        [
            'id'=>'123',
            'name'=>'动画C',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ],
        [
            'id'=>'124',
            'name'=>'动画D',
            'type'=>'content',
            'des'=>'动画',
            'price'=>'￥2,142.00'
        ] 
    ];
    $data = json_encode($data);
    $js = <<<JS
            var pageList = new Wskeee.demand.PageList({onItemSelected:onItemSelected});
            pageList.init($data);
            
            function onItemSelected(itemdata){
                console.log(itemdata.id);
            }
            
JS;
    $this->registerJs($js);
    PageListAssets::register($this);
?>
