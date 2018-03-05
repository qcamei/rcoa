<?php

use frontend\modules\demand\assets\ChartAsset;
use frontend\modules\scene\assets\SceneAsset;
use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', '{Scene}-{Statistics}',[
    'Scene' => Yii::t('app', 'Scene'),
    'Statistics' => Yii::t('app', 'Statistics'),
]);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="scene-statistics-index container statistics-content">
    <form class="form-horizontal">
        <div class="form-group">
            <label for="siteName" class="col-sm-2 control-label"><?php echo Yii::t('app', 'Site') ?></label>
            <div class="col-sm-10">
                <?php
                    echo Select2::widget([
                        'value' => $site,
                        'name' => 'siteName',
                        'data' => $siteName,
                        'maintainOrder' => true,
                        'hideSearch' => true,
                        'toggleAllSettings' => [
                            'selectLabel' => '<i class="glyphicon glyphicon-ok-circle"></i> 添加全部',
                            'unselectLabel' => '<i class="glyphicon glyphicon-remove-circle"></i> 取消全部',
                            'selectOptions' => ['class' => 'text-success'],
                            'unselectOptions' => ['class' => 'text-danger'],
                        ],
                        'options' => [
                            'placeholder' => Yii::t('app', 'Select Placeholder'),
                             'multiple' => true
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                            'allowClear' => true,
                            'maximumInputLength' => 10
                        ],
                    ]);
                ?>
            </div>
        </div>
        
        <div class="form-group">
          <label for="dateRange" class="col-sm-2 control-label"><?php echo Yii::t('app', 'Time Slot') ?></label>
          <div class="col-sm-10">
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
                            Yii::t('rcoa/teamwork', "Statistics-Prev-Week") => ["moment().startOf('week').subtract(1,'week')", "moment().endOf('week').subtract(1,'week')"],
                            Yii::t('rcoa/teamwork', "Statistics-This-Week") => ["moment().startOf('week')", "moment().endOf('week')"],
                            Yii::t('rcoa/teamwork', "Statistics-Prev-Month") => ["moment().startOf('month').subtract(1,'month')", "moment().endOf('month').subtract(1,'month')"],
                            Yii::t('rcoa/teamwork', "Statistics-This-Month") => ["moment().startOf('month')", "moment().endOf('month')"],
                            Yii::t('rcoa/teamwork', "Statistics-First-Half-Year") => ["moment().startOf('year')", "moment().startOf('year').add(5,'month').endOf('month')"],
                            Yii::t('rcoa/teamwork', "Statistics-Next-Half-Year") => ["moment().startOf('year').add(6,'month')", "moment().endOf('year')"],
                            Yii::t('rcoa/teamwork', "Statistics-Full-Year") => ["moment().startOf('year')", "moment().endOf('year')"],
                        ]
                    ],
                    
                ]);
            ?>
          </div>
        </div>
        
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-success"><?php echo Yii::t('rcoa/multimedia', 'Statistics-Submit') ?></button>
          </div>
        </div>
    </form>
    <hr/>
    
    <div class="content">
        <div class="col-lg-12">
            <div class="title"><?= Yii::t('app', 'Site')?>：</div>
            <div class="lists">
                <?php 
                    echo GridView::widget([
                        'dataProvider' => new ArrayDataProvider([
                            'allModels' => $books,
                            'pagination' => FALSE,
                        ]),
                        'layout' => "{items}\n{pager}",
                        'columns' => [
                            [
                                'label' => Yii::t('app', 'Name'),
                                'value'=> function($data){
                                    return $data['name'];
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                            [
                                'label' => Yii::t('app', 'Bespeak Number'),
                                'value'=> function($data){
                                    return $data['book_number'];
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                            [
                                'label' => Yii::t('app', 'Missed Bespeak Number'),
                                'format' => 'raw',
                                'value'=> function($data){
                                    return !empty($data['miss_number']) ? $data['miss_number'] . '<span style="color:' .
                                            (round($data['miss_number'] / $data['book_number'] * 100, 2) < 50 ? '#43c584' : '#ff0000') . '">（' .
                                                round($data['miss_number'] / $data['book_number'] * 100, 2) . '%）</span>' :
                                                    '无<span style="color:#43c584">（0%）';
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                            [
                                'label' => Yii::t('app', 'Utilization Ratio'),
                                'format' => 'raw',
                                'value'=> function($data){
                                    $date = Yii::$app->getRequest()->getQueryParam("dateRange");        //时间段
                                    $date_Arr = explode(" - ", $date);
                                    $all_date = (strtotime(date("Y-m-d")) - strtotime($data['earliest_time']))/3600/24 + 1; //总的时间段
                                    //计算利用率
                                    $num = !empty($date) ? 
                                        round($data['book_number'] / (((strtotime($date_Arr[1])-strtotime($date_Arr[0]))/3600/24 + 1) * 3) * 100, 2) :
                                            round($data['book_number'] / ($all_date * 3) * 100, 2);
                                    $num <= 50 ? $color = '#ff0000' : $color = '#43c584';   //选择颜色
                                    return '<span class="chart" data-percent="'.$num.'" data-bar-color="'.$color.'">'.
                                                '<span class="percent" style="color: '. $color.'"></span>'.
                                            '</span>';
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                        ]
                    ])
                ?>
                <center>
                    <?php if(count($books) > 0): ?>
                        <div class="summary">总计<b><?= count($books) - 1 ?></b>条数据。</div>
                    <?php endif; ?>
                </center>
            </div>
        </div>
        <!--根据接洽人来统计-->
        <div class="col-lg-12">
            <div class="title"><?= Yii::t('app', 'Booker')?>：</div>
            <div class="lists">
                <?php 
                    echo GridView::widget([
                        'dataProvider' => new ArrayDataProvider([
                            'allModels' => $booker,
                            'pagination' => FALSE,
                            'sort' => [
                                'attributes' => ['nickname', 'booker_number', 'miss_number', 'miss_rate'],
                            ],
                        ]),
                        'layout' => "{items}\n{summary}\n{pager}",
                        'columns' => [
                            [
                                'attribute' => 'nickname',
                                'label' => Yii::t('app', 'Name'),
                                'value'=> function($data){
                                    return $data['nickname'];
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                            [
                                'attribute' => 'booker_number',
                                'label' => Yii::t('app', 'Bespeak Number'),
                                'value'=> function($data){
                                    return $data['booker_number'];
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                            [
                                'attribute' => 'miss_number',
                                'label' => Yii::t('app', 'Missed Bespeak Number'),
                                'format' => 'raw',
                                'value'=> function($data){
                                    return !empty($data['miss_number']) ? $data['miss_number'] : '无';
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                            [
                                'attribute' => 'miss_rate',
                                'label' => Yii::t('app', 'Missed Bespeak Rate'),
                                'format' => 'raw',
                                'value'=> function($data){
                                    return !empty($data['miss_rate']) ? '<span style="color:' .
                                                    ($data['miss_rate'] < 50 ? '#43c584' : '#ff0000') . '">' .
                                                $data['miss_rate'] .'%</span>' :
                                            '<span style="color:#43c584">0%</span>';
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                        ]
                    ])
                ?>
            </div>
        </div>
        <!--根据编导来统计-->
        <div class="col-lg-12">
            <div class="title"><?= Yii::t('app', 'Director')?>：</div>
            <div class="lists">
                <?php 
                    echo GridView::widget([
                        'dataProvider' => new ArrayDataProvider([
                            'allModels' => $director,
                            'pagination' => FALSE,
                            'sort' => [
                                'attributes' => ['name', 'contact_number', 'score'],
                            ],
                        ]),
                        'layout' => "{items}\n{summary}\n{pager}",
                        'columns' => [
                            [
                                'attribute' => 'name',
                                'label' => Yii::t('app', 'Name'),
                                'value'=> function($data){
                                    return $data['name'];
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                            [
                                'attribute' => 'contact_number',
                                'label' => Yii::t('app', 'Contact Number'),
                                'value'=> function($data){
                                    return $data['contact_number'];
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                            [
                                'attribute' => 'score',
                                'label' => Yii::t('app', 'Score').'（100分）',
                                'value'=> function($data){
                                    return !empty($data['score']) ?$data['score'] : '无';
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                        ]
                    ])
                ?>
            </div>
        </div>
        <!--根据摄影师来统计-->
        <div class="col-lg-12">
            <div class="title"><?= Yii::t('app', 'Photographer')?>：</div>
            <div class="lists">
                <?php 
                    echo GridView::widget([
                        'dataProvider' => new ArrayDataProvider([
                            'allModels' => $photographer,
                            'pagination' => FALSE,
                            'sort' => [
                                'attributes' => ['name', 'contact_number', 'score'],
                            ],
                        ]),
                        'layout' => "{items}\n{summary}\n{pager}",
                        'columns' => [
                            [
                                'attribute' => 'name',
                                'label' => Yii::t('app', 'Name'),
                                'value'=> function($data){
                                    return $data['name'];
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                            [
                                'attribute' => 'contact_number',
                                'label' => Yii::t('app', 'Shoot Number'),
                                'value'=> function($data){
                                    return $data['contact_number'];
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                            [
                                'attribute' => 'score',
                                'label' => Yii::t('app', 'Score').'（100分）',
                                'value'=> function($data){
                                    return !empty($data['score']) ?$data['score'] : '无';
                                },
                                'headerOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                    ],
                                ],
                                'contentOptions' => [
                                    'style' => [
                                        'text-align' => 'center',
                                        'vertical-align' => 'middle',
                                    ],
                                ],
                            ],
                        ]
                    ])
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$js =   
<<<JS
   $(function() {
        $('.chart').easyPieChart({  
            size: 70,
            onStep: function(from, to, percent) {  
                $(this.el).find('.percent').text(Math.round(percent));  
            }  
        }); 
    });  
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    SceneAsset::register($this);
    ChartAsset::register($this);
?>
