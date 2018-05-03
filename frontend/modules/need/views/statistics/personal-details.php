<?php

use frontend\modules\need\assets\ModuleAssets;
use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', '{Statistics}-{Personal Details}',[
    'Statistics' => Yii::t('app', 'Statistics'),
    'Personal Details' => Yii::t('app', 'Personal Details'),
]);

$radioType = [
    '0' => '成本',
    '1' => '绩效',
]

?>

<div class="statistics statistics-personal-details">
    <form class="form-horizontal"  id="personal-details-form">
        <!--时间段-->
        <div class="form-group">
          <label for="dateRange" class="col-sm-1 control-label"><?php echo Yii::t('app', 'Time Slot') ?>：</label>
          <div class="col-sm-11">
            <?php
                echo DateRangePicker::widget([
                    'value'=>$dateRange,
                    'name' => 'dateRange',
                    //'presetDropdown' => true,
                    'hideInput' => true,
                    'convertFormat'=>true,
                    'pluginOptions'=>[
                        'locale'=>['format' => 'Y-m-d'],
                        'allowClear' => true,
                        'ranges' => [
                            Yii::t('app', "Statistics-Prev-Week") => ["moment().startOf('week').subtract(1,'week')", "moment().endOf('week').subtract(1,'week')"],
                            Yii::t('app', "Statistics-This-Week") => ["moment().startOf('week')", "moment().endOf('week')"],
                            Yii::t('app', "Statistics-Prev-Month") => ["moment().startOf('month').subtract(1,'month')", "moment().endOf('month').subtract(1,'month')"],
                            Yii::t('app', "Statistics-This-Month") => ["moment().startOf('month')", "moment().endOf('month')"],
                            Yii::t('app', "First Season") => ["moment().startOf('Q').quarter(1,'quarter')","moment().endOf('Q').quarter(1,'quarter')"],
                            Yii::t('app', "Second Season") => ["moment().startOf('Q').quarter(2,'quarter')","moment().endOf('Q').quarter(2,'quarter')"],
                            Yii::t('app', "Third Season") => ["moment().startOf('Q').quarter(3,'quarter')","moment().endOf('Q').quarter(3,'quarter')"],
                            Yii::t('app', "Fourth Season") => ["moment().startOf('Q').quarter(4,'quarter')","moment().endOf('Q').quarter(4,'quarter')"],
                            Yii::t('app', "Statistics-First-Half-Year") => ["moment().startOf('year')", "moment().startOf('year').add(5,'month').endOf('month')"],
                            Yii::t('app', "Statistics-Next-Half-Year") => ["moment().startOf('year').add(6,'month')", "moment().endOf('year')"],
                            Yii::t('app', "Statistics-Full-Year") => ["moment().startOf('year')", "moment().endOf('year')"],
                        ]
                    ],
                    
                ]);
            ?>
          </div>
        </div>
        <!--个人名称-->
        <div class="form-group">
            <label for="username" class="col-sm-1 control-label"><?php echo Yii::t('app', 'Personal Name') ?>：</label>
            <div  class="col-sm-4">
                <?php 
                echo Select2::widget([
                    'value'=> $username,
                    'name' => 'username',
                    'data' => $receive,
                    'options' => [
                        'placeholder' => Yii::t('app', 'All'),
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>
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
                <a id="export" role="button" class="btn btn-default"><?php echo Yii::t('app', 'Export') ?></a>
            </div>
        </div>
    </form>
    <hr/>
    <!--统计结果-->
    <div>
        <div class="summar-title">
            <?php if($type == 0): ?>
                <i class="fa fa-bar-chart"></i>&nbsp;总成本：
                <span class="num">￥<?= empty($totalCost['total_cost']) ? '0.00' : $totalCost['total_cost']; ?></span>
            <?php elseif ($type == 1): ?>
                <i class="fa fa-bar-chart"></i>&nbsp;总绩效：
                <span class="num">￥<?= empty($totalBonus['total_bonus']) ? '0.00' : $totalBonus['total_bonus']; ?></span>
            <?php endif;?>
        </div>
        <br/>
        <?php
            if($type == 0){
                echo GridView::widget([
                    'dataProvider' => new ArrayDataProvider([
                        'allModels' => $taskCost,
                    ]),
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => [
                                'width' => '40px'
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'Business'),
                            'value' => function ($data){
                                return $data['business_name'];
                            },
                            'headerOptions' => [
                                'class' => 'hidden-xs'
                            ],
                            'contentOptions' => [
                                'class' => 'hidden-xs'
                            ],       
                        ],
                        [
                            'label' => Yii::t('app', 'Layer ID'),
                            'value' => function ($data){
                                return $data['layer_name'];
                            },
                            'headerOptions' => [
                                'class' => 'hidden-xs'
                            ],
                            'contentOptions' => [
                                'class' => 'hidden-xs'
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'Profession ID'),
                            'value' => function ($data){
                                return $data['Profession_name'];
                            },
                            'headerOptions' => [
                                'class' => 'hidden-xs'
                            ],
                            'contentOptions' => [
                                'class' => 'hidden-xs'
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'Courses'),
                            'value' => function ($data){
                                return $data['course_name'];
                            },
                            'headerOptions' => [
                                'class' => 'hidden-xs'
                            ],
                            'contentOptions' => [
                                'class' => 'hidden-xs'
                            ],
                        ],
                        [
                            'label' => Yii::t('app', '{Need}{Name}',['Need' => Yii::t('app', 'Need'), 'Name' => Yii::t('app', 'Name')]),
                            'value' => function ($data){
                                return $data['task_name'];
                            }
                        ],
                        [
                            'label' => Yii::t('app', '{Finish}{Time}',['Finish' => Yii::t('app', 'Finish'), 'Time' => Yii::t('app', 'Time')]),
                            'value' => function ($data){
                                return !empty($data['finish_time']) ? date('Y-m-d H:i',$data['finish_time']) : null;
                            },
                            'headerOptions' => [
                                'class' => 'hidden-xs'
                            ],
                            'contentOptions' => [
                                'class' => 'hidden-xs'
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'Receive By'),
                            'headerOptions' => [
                                'width' => '80px'
                            ],
                            'value' => function ($data){
                                return $data['nickname'];
                            }
                        ],
                        [
                            'label' => Yii::t('app', '{Actual}{Cost}',['Actual' => Yii::t('app', 'Actual'), 'Cost' => Yii::t('app', 'Cost')]),
                            'value' => function ($data){
                                return !empty($data['reality_cost']) ? '￥' . $data['reality_cost'] : null;
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'headerOptions' => [
                                'width' => '30px'
                            ],
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $data, $key) {
                                     $options = [
                                        'title' => Yii::t('app', 'View'),
                                        'aria-label' => Yii::t('app', 'View'),
                                        'data-pjax' => '0',
                                    ];
                                    $buttonHtml = [
                                        'name' => '<span class="glyphicon glyphicon-eye-open"></span>',
                                        'url' => ['task/view', 'id' => $data['id']],
                                        'options' => $options,
                                        'symbol' => '&nbsp;',
                                        'conditions' => true,
                                        'adminOptions' => true,
                                    ];
                                    return Html::a($buttonHtml['name'],$buttonHtml['url'],$buttonHtml['options']).' ';
                                },
                            ],
                        ],
                    ],
                ]);
            } else {
                echo GridView::widget([
                    'dataProvider' => new ArrayDataProvider([
                        'allModels' => $taskBonus,
                    ]),
                    'layout' => "{items}\n{summary}\n{pager}",
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => [
                                'width' => '40px'
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'Layer ID'),
                            'value' => function ($data){
                                return $data['layer_name'];
                            },
                            'headerOptions' => [
                                'class' => 'hidden-xs'
                            ],
                            'contentOptions' => [
                                'class' => 'hidden-xs'
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'Profession ID'),
                            'value' => function ($data){
                                return $data['Profession_name'];
                            },
                            'headerOptions' => [
                                'class' => 'hidden-xs'
                            ],
                            'contentOptions' => [
                                'class' => 'hidden-xs'
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'Courses'),
                            'value' => function ($data){
                                return $data['course_name'];
                            },
                            'headerOptions' => [
                                'class' => 'hidden-xs'
                            ],
                            'contentOptions' => [
                                'class' => 'hidden-xs'
                            ],
                        ],
                        [
                            'label' => Yii::t('app', '{Need}{Name}',['Need' => Yii::t('app', 'Need'), 'Name' => Yii::t('app', 'Name')]),
                            'value' => function ($data){
                                return $data['task_name'];
                            }
                        ],
                        [
                            'label' => Yii::t('app', '{Finish}{Time}',['Finish' => Yii::t('app', 'Finish'), 'Time' => Yii::t('app', 'Time')]),
                            'value' => function ($data){
                                return !empty($data['finish_time']) ? date('Y-m-d H:i',$data['finish_time']) : null;
                            },
                            'headerOptions' => [
                                'class' => 'hidden-xs'
                            ],
                            'contentOptions' => [
                                'class' => 'hidden-xs'
                            ],
                        ],
                        [
                            'label' => Yii::t('app', 'Receive By'),
                            'value' => function ($data){
                                return $data['nickname'];
                            }
                        ],
                        [
                            'label' => Yii::t('app', '{Actual}{Content}{Cost}',['Actual' => Yii::t('app', 'Actual'),'Content' => Yii::t('app', 'Content'),  'Cost' => Yii::t('app', 'Cost')]),
                            'value' => function ($data){
                                return !empty($data['reality_cost']) ? '￥' . $data['reality_cost'] : null;
                            },
                            'headerOptions' => [
                                'class' => 'hidden-xs'
                            ],
                            'contentOptions' => [
                                'class' => 'hidden-xs'
                            ],
                        ],
                        [
                            'label' => Yii::t('app', '{Actual}{Bonus}',['Actual' => Yii::t('app', 'Actual'), 'Bonus' => Yii::t('app', 'Bonus')]),
                            'value' => function ($data){
                                return !empty($data['reality_bonus']) ? '￥' . $data['reality_bonus'] : null;
                            }
                        ],
                        [
                            'label' => Yii::t('app', 'Bonus Ratio'),
                            'value' => function ($data){
                                return !empty($data['performance_percent']) ? $data['performance_percent'] * 100 . '%' : null;
                            }
                        ],
                        [
                            'label' => Yii::t('app', '{Personal}{Bonus}',['Personal' => Yii::t('app', 'Personal'), 'Bonus' => Yii::t('app', 'Bonus')]),
                            'value' => function ($data){
                                return !empty($data['personal_bonus']) ? '￥' . $data['personal_bonus'] : null;
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'headerOptions' => [
                                'width' => '30px'
                            ],
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $data, $key) {
                                     $options = [
                                        'title' => Yii::t('app', 'View'),
                                        'aria-label' => Yii::t('app', 'View'),
                                        'data-pjax' => '0',
                                    ];
                                    $buttonHtml = [
                                        'name' => '<span class="glyphicon glyphicon-eye-open"></span>',
                                        'url' => ['task/view', 'id' => $data['id']],
                                        'options' => $options,
                                        'symbol' => '&nbsp;',
                                        'conditions' => true,
                                        'adminOptions' => true,
                                    ];
                                    return Html::a($buttonHtml['name'],$buttonHtml['url'],$buttonHtml['options']).' ';
                                },
                            ],
                        ],
                    ],
                ]);
            }
        ?>
    </div>      
</div>

<?php

$js = <<<JS
        
        /** 导出数据 */    
        $('#export').click(function(){
            location.href = "/need/export/personal-run?" + $('#personal-details-form').serialize();
        });
        
JS;
    $this->registerJs($js, View::POS_READY);
    ModuleAssets::register($this);
?>
