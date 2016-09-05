<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TeamworkTool;
use yii\grid\GridView;
use yii\helpers\Html;

?>

<?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'tableOptions' => ['class' => 'table table-striped table-list'],
            'columns' => [
                [
                    'class' => 'frontend\modules\teamwork\components\ItemListTd',
                    'label' => '',
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return '<span class="team-span">'.$model->team->name.'</span>';
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '45px' 
                        ],
                    ],
                    'contentOptions' =>[
                        //'class'=>'hidden-xs',
                        'style' => 'white-space: nowrap;'
                    ],
                ],
                [
                    'class' => 'frontend\modules\teamwork\components\ItemListTd',
                    'label' => Yii::t('rcoa/teamwork', 'Item Type'),
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return $model->project->itemType->name;
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
                    'class' => 'frontend\modules\teamwork\components\ItemListTd',
                    'label' => Yii::t('rcoa/teamwork', 'Item'),
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return $model->project->item->name;
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
                    'class' => 'frontend\modules\teamwork\components\ItemListTd',
                    'label' => Yii::t('rcoa/teamwork', 'Item Child'),
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return $model->project->itemChild->name;
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
                    'class' => 'frontend\modules\teamwork\components\ItemListTd',
                    'label' => Yii::t('rcoa/teamwork', 'Course ID'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return '<div class="course-name">'.$model->course->name.'</div>'.
                               Html::beginTag('div', [
                                        'class' => 'progress table-list-progress',
                                        'style' => 'height:12px;margin:2px 0;border-radius:0px;'
                                    ]).
                                    Html::beginTag('div', [
                                        'class' => 'progress-bar progress-bar',
                                        'style' => 'width:'.(int)($model->progress * 100).'%;line-height: 12px;font-size: 10px;',
                                    ]).
                                    (int)($model->progress * 100).'%'.
                                    Html::endTag('div').
                                Html::endTag('div');
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
                            'padding' => '2px 4px'
                        ],
                    ],

                ],
                [
                    'class' => 'frontend\modules\teamwork\components\ItemListTd',
                    'label' => Yii::t('rcoa/teamwork', 'Weekly'),
                    'format' => 'raw',
                    'value' => function($model){
                        /* @var $model CourseManage */
                        /* @var $twTool TeamworkTool */
                       $twTool = Yii::$app->get('twTool');
                       $week = $twTool->getWeek(date('Y-m-d', time()));
                       $result = $twTool->getWeeklyInfo($model->id, $week['start'], $week['end']);
                       return empty($result) ? '' : 
                              Html::img(['/filedata/teamwork/image/already_write_weekly.png']);
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