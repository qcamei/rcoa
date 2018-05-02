<?php

use common\models\demand\DemandTask;
use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use frontend\modules\teamwork\utils\TeamworkTool;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

/* @var $this View */
/* @var $twTool TeamworkTool */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/teamwork', 'Teamwork Course');
$this->params['breadcrumbs'][] = $this->title;

$courseIds = ArrayHelper::getColumn($dataProvider->allModels, 'id');
$weekly = ArrayHelper::map($twTool->getWeeklyInfo($courseIds, $twTool->getWeek(date('Y-m-d', time()))), 'course_id', 'create_time');
foreach ($dataProvider->allModels as $model)
    $model->isExistWeekly = isset($weekly[$model->id]);

CourseManage::$progress = ArrayHelper::map($twTool->getCourseProgress($courseIds)->all(), 'id', 'progress');

?>

<div class="container course-manage-index item-manage">
    
    <?= $this->render('_search_detai',[
        'params' => $params,
        //条件
        'itemTypes' => $itemTypes,
        'items' => $items,
        'itemChilds' => $itemChilds,
        'courses' => $courses,
        'teams' => $teams,
    ]); ?>
        
    <div id="course-manage-index">
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
                        /* @var $model CourseManage */
                        return $model->demandTask->mode == DemandTask::MODE_NEWBUILT ?
                                Html::img(['/filedata/demand/image/mode_newbuilt.png']) : 
                                Html::img(['/filedata/demand/image/mode_reform.png']);
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '16px',
                            'padding-right' => '4px'
                        ],
                    ],
                    'contentOptions' =>[
                        //'class'=>'hidden-xs',
                        'style' => 'white-space: nowrap; padding-right:4px'
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Statistics-Team'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return !empty($model->team_id) ? '<span class="team-span">'.$model->team->name.'</span>' : null;;
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '45px',
                            'padding-left' => '4px;',
                            'padding-right' => '4px;',
                        ],
                    ],
                    'contentOptions' =>[
                        //'class'=>'hidden-xs',
                        'style' => 'white-space: nowrap;padding-left:4px;padding-right:4px;'
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Item Type'),
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return !empty($model->demandTask->item_type_id) ? $model->demandTask->itemType->name : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-sm hidden-xs',
                        ],
                        'style' => [
                            'width' => '90px' 
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'hidden-sm hidden-xs course-name',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Item'),
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return !empty($model->demandTask->item_id) ? $model->demandTask->item->name : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-sm hidden-xs',
                        ],
                        'style' => [
                            'width' => '100px' 
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'hidden-sm hidden-xs course-name',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Item Child'),
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return !empty($model->demandTask->item_child_id) ? $model->demandTask->itemChild->name : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '250px' 
                        ],
                    ],
                    'contentOptions' =>[
                        'class' => 'course-name hidden-xs course-name',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Course ID'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return !empty($model->demandTask->course_id) ? '<div class="course-name">'.($model->demandTask->course->name).'</div>'.
                               Html::beginTag('div', [
                                        'class' => 'progress table-list-progress',
                                        'style' => 'height:12px;margin:2px 0;border-radius:0px;'
                                    ]).
                                    Html::beginTag('div', [
                                        'class' => 'progress-bar progress-bar',
                                        'style' => 'width:'.CourseManage::$progress[$model->id].'%;line-height: 12px;font-size: 10px;',
                                    ]).(!empty(CourseManage::$progress[$model->id]) ? CourseManage::$progress[$model->id] : 0).'%'.
                                    Html::endTag('div').
                                Html::endTag('div') : null;
                    },
                    'headerOptions' => [
                        'style' => [
                            'max-width' => '300px',
                            'min-width' => '70px',
                        ],
                    ],
                    'contentOptions' =>[
                        'style' => [
                            'max-width' => '300px', 
                            'max-width' => '70px',
                            'padding' => '2px 8px'
                        ],
                    ],

                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Weekly'),
                    'format' => 'raw',
                    'value' => function ($model){
                        return $model->isExistWeekly ? Html::img(['/filedata/teamwork/image/already_write_weekly.png']) : '';
                    },
                    'headerOptions'=>[
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style'=> [
                            'width' => '15px',
                        ]
                    ],
                    'contentOptions' =>[
                        'class' => 'hidden-xs',
                    ],
                ],
                [
                    'class' => 'frontend\modules\teamwork\components\ItemActBtnCol',
                    'label' => Yii::t('rcoa', 'Operating'),
                    'contentOptions' =>[
                        'style'=> [
                            'width' => '90px',
                            'padding' =>'4px',
                        ],
                     ],
                     'headerOptions'=>[
                        'style'=> [
                            'width' => '125px',
                        ]
                    ],
                ],
            ],
        ]); ?>
        
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
</div>

<?= $this->render('/default/_footer',[
    'twTool' => $twTool
]) ?>

<?php
    TwAsset::register($this);
?>