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
        'params' => $params,
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
                    return $model->level == WorksystemTask::LEVEL_URGENT ? 
                                Html::img(['/filedata/worksystem/image/flag.png'], [
                                    'class' => 'flag']) : '';
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
                'value'=> function($model){
                    /* @var $model WorksystemTask */
                    return !empty($model->task_type_id) ? 
                            '<span class="task-type-span">'.$model->worksystemTaskType->name.'</span>' : '';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '34px',
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
                'value'=> function($model){
                    /* @var $model WorksystemTask */
                    if(!empty($model->external_team) && !empty($model->create_team)){
                        if($model->external_team != $model->create_team && $model->getIsSeekEpiboly())
                            return '<span class="team-span team-span-left">'.$model->createTeam->name.'</span>'. Html::img(['/filedata/worksystem/image/brace.png'], ['class' => 'brace']) .'<span class="team-span team-span-left epiboly-team-span">'.$model->externalTeam->name.'</span>';
                        else if($model->external_team != $model->create_team && $model->getIsSeekBrace())
                            return '<span class="team-span team-span-left">'.$model->createTeam->name.'</span>'. Html::img(['/filedata/worksystem/image/brace.png'], ['class' => 'brace']) . '<span class="team-span team-span-left">'.$model->externalTeam->name.'</span>';
                        else 
                            return '<span class="team-span">'.$model->createTeam->name.'</span>';
                    }else
                        return null;                      
                },
                'headerOptions' => [
                    'style' => [
                        'width' => '105px',
                        'padding' => '8px 2px',
                        'text-align' => 'center;'
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'padding' => '8px 2px'
                    ],
                ],
            ],
            /*[
                'label' => Yii::t('rcoa/worksystem', 'Item Type ID'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model WorksystemTask 
                    return !empty($model->item_type_id) ? $model->itemType->name : null;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs hidden-sm hidden-md',
                    ],
                    'style' => [
                        'width' => '100px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name list-td hidden-sm hidden-md',
                ],
            ],*/
            [
                'label' => Yii::t('rcoa/worksystem', 'Item ID'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model WorksystemTask */
                    return !empty($model->item_id) ? $model->item->name : null;
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
                'value'=> function($model){
                    /* @var $model WorksystemTask */
                    return !empty($model->item_child_id) ? $model->itemChild->name : null;
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
                'value'=> function($model){
                    /* @var $model WorksystemTask */
                    return !empty($model->course_id) ? $model->course->name : null;
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
                'value'=> function($model){
                    /* @var $model WorksystemTask */
                    return '<div class="course-name">'.(!empty($model->name) ? $model->name : null).'</div>'.
                            Html::beginTag('div', [
                                'class' => 'progress progress-bg',
                            ]).Html::beginTag('div', [
                                    'class' => 'progress-bar progress-words', 
                                    'style' => 'width:'.($model->progress ).'%;',
                               ]).($model->progress).'%'.
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
                'value'=> function($model){
                    /* @var $model WorksystemTask */
                    return !empty($model->plan_end_time) ? $model->plan_end_time : null;
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
                'value'=> function($model){
                    /* @var $model WorksystemTask */
                    return !empty($model->create_by) ? $model->createBy->nickname : null;
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
                'value'=> function($model) use($producer){
                    /* @var $model WorksystemTask */
                    return isset($producer[$model->id]) ? (is_array($producer[$model->id]) ? implode(',', $producer[$model->id]) : $producer[$model->id]) : null;
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
                    'view' => function ($url, $model) use($operation) {
                        /* @var $model WorksystemTask */
                        $options = [
                            'class' => $model->getIsStatusCancel() ? 'btn btn-danger btn-sm' :
                                ($model->getIsStatusCompleted() ? 'btn btn-success btn-sm' : 
                                    (!empty($operation[$model->id]) ? 'btn btn-primary btn-sm' : 'btn btn-default btn-sm')),
                            'style' => 'width: 55px;'
                        ];
                        return Html::a($model->getStatusName(), [
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
        
    $('#submit').click(function(){
        $('#worksystem-task-search').submit();
    })
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>