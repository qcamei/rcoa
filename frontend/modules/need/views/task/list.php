<?php

use common\models\need\NeedTask;
use common\models\need\searchs\NeedTaskSearch;
use frontend\modules\need\assets\ModuleAssets;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;


/* @var $this View */
/* @var $searchModel NeedTaskSearch */
/* @var $dataProvider ActiveDataProvider */

ModuleAssets::register($this);

$this->title = Yii::t('app', 'Need Tasks');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container need-task-index">
  
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{summary}\n{pager}",
        'summaryOptions' => ['class' => 'hidden'],
        'pager' => [
            'options' => ['class' => 'hidden']
        ],
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                //'attribute' => 'level',
                'label' => '',
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedTask */
                    return $model->level ? '<i class="fa fa-bolt danger">' : '';
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '16px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        //'td' => 'hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'font-size' => '18px;',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'business_id',
                'label' => Yii::t('app', 'Business ID'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedTask */
                    return !empty($model->business_id) ? $model->business->name : null;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '100px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        'td' => 'course-name hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'layer_id',
                'label' => Yii::t('app', 'Layer ID'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedTask */
                    return !empty($model->layer_id) ? $model->layer->name : null;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '120px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        'td' => 'course-name hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'profession_id',
                'label' => Yii::t('app', 'Profession ID'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedTask */
                    return !empty($model->profession_id) ? $model->profession->name : null;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '150px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        'td' => 'course-name hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'course_id',
                'label' => Yii::t('app', 'Course ID'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedTask */
                    return !empty($model->course_id) ? $model->course->name : null;
                },
                'headerOptions' => [
                    'class'=>[
                        'th' => 'hidden-xs',
                    ],
                    'style' => [
                        'width' => '180px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        'td' => 'course-name hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'task_name',
                'label' => Yii::t('app', 'Task Name'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedTask */
                    return $model->task_name . 
                        '<div class="progress table-list-progress" style="height: 12px; margin: 2px 0; border-radius:0px;">' .
                            '<div class="progress-bar" style="width: ' . NeedTask::$progressMap[$model->status] . '%; line-height: 12px; font-size: 10px;">' .
                                NeedTask::$progressMap[$model->status] . '%' .
                            '</div>' . 
                        '</div>';
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'min-width' => '80px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        'td' => 'course-name',
                    ],
                    'style' => [
                        'padding' => '2px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'need_time',
                'label' => Yii::t('app', 'Need Time'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedTask */
                    return '<span class="danger">' . date('Y-m-d H:i', $model->need_time) . '</span>';
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '80px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        //'td' => 'hidden-xs'
                    ],
                    'style' => [
                        'padding' => '4px',
                        'font-size' => '10px',
                    ],
                ],
            ],
            [
                //'attribute' => 'created_by',
                'label' => Yii::t('app', 'Created By'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedTask */
                    return !empty($model->created_by) ? $model->createdBy->nickname : null;
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '50px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        //'td' => 'hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'plan_content_cost',
                'label' => Yii::t('app', 'Plan Content Cost'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedTask */
                    $planCost = $model->plan_content_cost + $model->plan_content_cost * $model->performance_percent;
                    return '￥' . number_format($planCost / 10000, 2, '.', '') . '万';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '70px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        'td' => 'hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => Yii::t('rcoa', 'Operating'),
                'buttons' => [
                    'view' => function ($url, $model){
                        /* @var $model NeedTask */
                        $colour = $model->getIsWaitReceive() ? 'btn-primary' : 'btn-default';
                        $options = [
                            'class' => 'btn ' . $colour . ' btn-sm',
                            'target' => '_blank',
                        ];
                        return Html::a($model->getStatusName(), ['view', 'id' => $model->id], $options);
                    },
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '65px',
                        'padding' => '8px 4px;',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'width' => '65px',
                        'padding' => '6px 4px;',
                    ],
                ],
                'template' => '{view}',
            ],
        ],
    ]); ?>
    
     <?php
        $page = !isset($param['page']) ? 1 :$param['page'];
        $pageCount = ceil($totalCount / 20);
        if($pageCount > 0){
            echo '<div class="summary">' . 
                    '第<b>' . (($page * 20 - 20) + 1) . '</b>-<b>' . ($page != $pageCount ? $page * 20 : $totalCount) .'</b>条，总共<b>' . $totalCount . '</b>条数据。' .
                '</div>';
        }
        
        echo LinkPager::widget([  
            'pagination' => new Pagination([
                'totalCount' => $totalCount,  
            ]),  
        ])
    ?>
    
</div>

<?php
$js = 
<<<JS
   //当前页面得到焦点刷新
    window.onblur = function(){
        reload();
    }
    function reload(){
        window.onfocus = function(){
            $("body").load(window.location.href); 
        }
    }
JS;
    $this->registerJs($js,  View::POS_READY);
?>