<?php

use common\models\demand\DemandTask;
use common\models\worksystem\searchs\WorksystemTaskSearch;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

/* @var $this View */
/* @var $searchModel WorksystemTaskSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/worksystem', 'Worksystem Tasks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container worksystem worksystem-task-index">

    <?php  //$this->render('_search',['model' => $model, 'mark' => $mark,]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{summary}\n{pager}",
        'summaryOptions' => [
            //'class' => 'summary',
            'class' => 'hidden',
            //'style' => 'float: left'
        ],
        'pager' => [
            'options' => [
                //'class' => 'pagination',
                'class' => 'hidden',
                //'style' => 'float: right; margin: 0px;'
            ]
        ],
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                'label' => '',
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model WorksystemTask */
                    return ;
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '16px',
                        'padding-left' => '4px',
                        'padding-right' => '4px'
                    ],
                ],
                'contentOptions' =>[
                    //'class'=>'hidden-xs',
                    'style' => 'white-space: nowrap; padding-left:4px;padding-right:4px'
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('rcoa', 'Operating'),
                'buttons' => [
                    'view' => function ($url, $model) {
                        /* @var $model WorksystemTask */
                        $options = [
                            'class' => '',
                            'style' => 'width: 55px;'
                        ];
                        return Html::a('', [
                            'view', 'id' => $model->id], $options);
                    },
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '60px',
                        'padding' => '8px 2px;',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'width' => '60px',
                        'padding' => '4px 2px;',
                    ],
                ],
                'template' => '{view}',
            ],
        ],
    ]); ?>
    
    <div class="summary">总共<b><?= $count ?></b>条数据</div>
        
    <?= LinkPager::widget([  
        'pagination' => new Pagination([
            'totalCount' => $count,  
        ]),  
    ]) ?> 
    
</div>

<?php
$js = 
<<<JS
    /** 格式化所有价钱 */
    format(".total-price");
    /** 价格格式化 */
    function format(obj){
        $(obj).each(function(){
            var con = trim($(this).html()).split('￥');
            $(this).html('<span class="big" style="font-size: 14px;">' + $(this).html().split('.')[0] + '.</span><span class="small">' + $(this).html().split('.')[1] + '</span>');
        });
    }
    /** 正则匹配 */
    function trim(str){ 
　　     return str.replace(/(^\s*)|(\s*$)/g, "");
　　}
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>