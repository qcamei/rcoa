<?php

use common\models\mconline\McbsActionLog;
use common\models\mconline\McbsCourseUser;
use common\models\mconline\searchs\McbsActionLogSearch;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel McbsActionLogSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Mcbs Courses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcbs-actlog-index actlog">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-list'],
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
        'columns' => [
            [
                'label' => Yii::t('app', 'Action'),
                'format' => 'raw',
                'value'=> function ($model) {
                    /* @var $model McbsActionLog */
                    return $model->action;
                },
                'headerOptions' => [
                    'style' => [
                        'width' => '110px',
                        'padding' => '8px',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'padding' => '8px',
                    ]
                ],
            ],
            [
                'label' => Yii::t('app', 'Title'),
                'format' => 'raw',
                'value'=> function ($model) {
                    /* @var $model McbsActionLog */
                    return $model->title;
                },
                'headerOptions' => [
                    'style' => [
                        'width' => '100px',
                        'padding' => '8px',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'padding' => '8px',
                    ]
                ],
            ],
            [
                'label' => Yii::t('app', 'Content'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model McbsActionLog */
                    return $model->content;
                },
                'headerOptions' => [
                    'style' => [
                        'max-width' => '200px',
                        'min-width' => '55px',
                        'padding' => '8px',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'max-width' => '200px',
                        'min-width' => '55px',
                        'padding' => '8px',
                    ],
                    'class'=> 'course-name'
                ],
            ],
            [
                'label' => Yii::t('app', 'Create By'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model McbsActionLog */
                    return !empty($model->created_by) ? $model->createBy->nickname : null;
                },
                'headerOptions' => [
                    'style' => [
                        'width' => '100px',
                        'padding' => '8px',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'padding' => '8px',
                    ]
                ],
            ],
            [
                'label' => Yii::t('app', 'Time'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model McbsActionLog */
                    return date('Y-m-d H:i', $model->created_at);
                },
                'headerOptions' => [
                    'style' => [
                        'width' => '85px',
                        'padding' => '8px',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'font-size'=>'10px',
                        'padding' => '2px 8px',
                    ],
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => Yii::t('app', 'Operating'),
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        /* @var $model McbsCourseUser */
                         $options = [
                            'class' => 'btn btn-sm btn-default',
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                            'onclick' => 'view($(this));return false;'
                        ];
                        $buttonHtml = [
                            'name' => '<span class="fa fa-eye"></span>',
                            'url' => ['log-view', 'id' => $model->id],
                            'options' => $options,
                            'symbol' => '&nbsp;',
                            'conditions' => true,
                            'adminOptions' => true,
                        ];
                        return Html::a($buttonHtml['name'],$buttonHtml['url'],$buttonHtml['options']);
                        //return ResourceHelper::a($buttonHtml['name'], $buttonHtml['url'],$buttonHtml['options'],$buttonHtml['conditions']);
                    }
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '45px',
                        'padding' => '8px 0',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'padding' => '4px 0px',
                    ],
                ],
                'template' => '{view}',
            ],
        ],
    ]); ?>
    
    <div class="col-xs-12">
        <center>
        <?php
            if($page == null)
                echo Html::a('查看更多',['course-make/log-index','course_id' => $course_id,'relative_id'=>$relative_id,'page'=>$dataProvider->totalCount],[
                    'onclick'=>'more($(this));return false;']) 
        ?>
        </center>
    </div>
    
</div>

<?php
$js = 
<<<JS
   
    //点击加载更多
    function more(elem){
        $("#action-log").load(elem.attr("href")); 
    }    
   
    //课程操作详情弹出框
    function view(elem){
        $(".myModal").html("");
        $('.myModal').modal("show").load(elem.attr("href"));
    }    
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>