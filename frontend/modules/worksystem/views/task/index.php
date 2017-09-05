<?php

use common\models\worksystem\searchs\WorksystemTaskSearch;
use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
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

    <?= $this->render('_search',[
        'params' => $param,
        //条件
        'itemTypes' => $itemTypes,
        'items' => $items,
        'itemChilds' => $itemChilds,
        'courses' => $courses,
        'taskTypes' => ArrayHelper::map($taskTypes, 'id', 'name'),
        'createTeams' => $createTeams,
        'externalTeams' => $externalTeams,
        'createBys' => $createBys,
        'producers' => $producers,
    ]) ?>
    
    <?php 
       echo GridView::widget([
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
                   'value'=> function ($data) {
                       return $data['level'] ? Html::img(['/filedata/worksystem/image/flag.png'], ['class' => 'flag']) : '';
                   },
                   'headerOptions' => [
                       'style' => [
                           'width' => '20px',
                           'padding' => '8px 2px',
                       ],
                   ],
                   'contentOptions' =>[
                       'style' => [
                           'padding' => '8px 2px',
                       ]
                   ],
               ],
               [
                   'label' => Yii::t('rcoa/worksystem', 'Task Type'),
                   'format' => 'raw',
                   'value'=> function($data){
                       return !empty($data['task_type_name']) ? '<span class="task-type-span">'.$data['task_type_name'].'</span>' : '';
                    },
                   'headerOptions' => [
                       'class'=>[
                           'th'=>'hidden-xs',
                       ],
                       'style' => [
                           'width' => '65px',
                           'padding' => '8px 2px;'  
                       ],
                   ],
                   'contentOptions' =>[
                       'class'=>'hidden-xs',
                       'style' => [
                           'padding' => '8px 2px;'  
                       ],
                   ],
               ],
               [
                   'label' => Yii::t('rcoa/worksystem', 'Create → Brace'),
                   'format' => 'raw',
                   'value'=> function($data){
                        if(!empty($data['external_team_name']) && $data['is_epiboly'] == WorksystemTask::SEEK_EPIBOLY_MARK)
                            return "<span class=\"team-span team-span-left\">{$data['create_team_name']}</span>".Html::img(['/filedata/worksystem/image/brace.png'], ['class' => 'brace'])."<span class=\"team-span team-span-left epiboly-team-span\">{$data['external_team_name']}</span>";
                        else if(!empty($data['external_team_name']) && $data['is_brace'] == WorksystemTask::SEEK_BRACE_MARK)
                            return "<span class=\"team-span team-span-left\">{$data['create_team_name']}</span>".Html::img(['/filedata/worksystem/image/brace.png'], ['class' => 'brace'])."<span class=\"team-span team-span-left\">{$data['external_team_name']}</span>";
                        else 
                             return "<span class=\"team-span\">{$data['create_team_name']}</span>";
                    },
                   'headerOptions' => [
                       'style' => [
                           'width' => '105px',
                           'padding' => '8px 2px',
                       ],
                   ],
                   'contentOptions' =>[
                       'style' => [
                           'padding' => '8px 2px'
                       ],
                   ],
               ],
               [
                'label' => Yii::t('rcoa/worksystem', 'Item ID'),
                'format' => 'raw',
                'value'=> function($data){
                    return !empty($data['item_name']) ? $data['item_name'] : null;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs hidden-sm hidden-md',
                    ],
                    'style' => [
                        'width' => '80px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name list-td hidden-sm hidden-md',
                ],
            ],
            [
                'label' => Yii::t('rcoa/worksystem', 'Item Child ID'),
                'format' => 'raw',
                'value'=> function($data){
                    return !empty($data['item_child_name']) ? $data['item_child_name'] : null;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '135px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name list-td hidden-sm',
                ],
            ],
            [
                'label' => Yii::t('rcoa/worksystem', 'Course ID'),
                'format' => 'raw',
                'value'=> function($data){
                    return !empty($data['item_course_name']) ? $data['item_course_name'] : null;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '126px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name list-td hidden-sm',
                ],
            ],
            [
                'label' => Yii::t('rcoa/worksystem', 'Name'),
                'format' => 'raw',
                'value'=> function($data){
                    return '<div class="course-name">'.(!empty($data['name']) ? $data['name'] : null).'</div>'.
                            Html::beginTag('div', [
                                'class' => 'progress progress-bg',
                            ]).Html::beginTag('div', [
                                    'class' => 'progress-bar progress-words', 
                                    'style' => 'width:'.($data['progress']).'%;',
                               ]).($data['progress']).'%'.
                               Html::endTag('div').
                            Html::endTag('div');
                },
                'headerOptions' => [
                    'style' => [
                        
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'list-td',
                    'style' => [
                        'padding' => '2px 4px',
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa/worksystem', 'Demand Time'),
                'format' => 'raw',
                'value'=> function($data){
                    return !empty($data['plan_end_time']) ? $data['plan_end_time'] : null;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '83px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                    'style' => [
                        'font-size' => '10px;',
                        'padding' => '2px 8px'
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa', 'Create By'),
                'format' => 'raw',
                'value'=> function($data){
                    return !empty($data['create_by']) ? $data['create_by'] : null;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '58px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'list-td hidden-xs',
                ],
            ],
            [
                'label' => Yii::t('rcoa/worksystem', 'Producer'),
                'format' => 'raw',
                'value'=> function($data){
                    return !empty($data['producer_nickname']) ? $data['producer_nickname'] : null;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '64px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'list-td hidden-xs course-name',
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('rcoa', 'Operating'),
                'buttons' => [
                    'view' => function ($url, $data) use($operation) {
                        $options = [
                            'class' => isset($operation[Yii::$app->user->id][$data['id']]) ? 
                                       ($operation[Yii::$app->user->id][$data['id']] == WorksystemTask::STATUS_CANCEL ? 'btn btn-danger btn-sm' : 
                                       ($operation[Yii::$app->user->id][$data['id']] == WorksystemTask::STATUS_COMPLETED ? 'btn btn-success btn-sm' : 
                                       ($operation[Yii::$app->user->id][$data['id']] ? 'btn btn-primary btn-sm' : 'btn btn-default btn-sm'))) : 
                                       ($data['status'] == WorksystemTask::STATUS_CANCEL ? 'btn btn-danger btn-sm' : ($data['status'] == WorksystemTask::STATUS_COMPLETED ? 'btn btn-success btn-sm' : 'btn btn-default btn-sm')),
                            'style' => 'width: 55px;'
                        ];
                        return Html::a(WorksystemTask::$statusNmae[$data['status']], ['view', 'id' => $data['id']], $options);
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
       ]); 
    ?>
    
    <?php
        $page = !isset($param['page']) ? 1 :$param['page'];
        $pageCount = ceil($totalCount/20);
        if($pageCount > 0)
            echo "<div class=\"summary\">第<b>".(($page*20-20)+1)."</b>-<b>".($page!=$pageCount?$page*20:$totalCount)."</b>条，总共<b>{$totalCount}</b>条数据.</div>";
    ?>
        
    <?= LinkPager::widget([  
        'pagination' => new Pagination([
            'totalCount' => $totalCount,  
        ]),  
    ]) ?> 
    
</div>

<?php
$js = 
<<<JS
        
    $('#submit').click(function(){
        $('#worksystem-task-search').submit();
    })
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>