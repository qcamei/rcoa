<?php

use common\models\scene\SceneBook;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' => false,
    'tableOptions' => ['class' => 'table table-striped table-week'],
    'columns' => [
        [
            'class' => 'frontend\modules\scene\components\SceneBookListWeek',
            'format' => 'raw',
            'attribute' => 'date',
            'label' => '时间',
            'value' => function($model) {
                return date('m/d ', strtotime($model->date)).'</br>' .Yii::t('rcoa', 'Week ' . date('D', strtotime($model->date)));
            },
            'headerOptions' => [
                'style'=>[
                    'width' => '45px',
                    'padding' => '4px'
                ]
            ],
            'contentOptions' =>[
                'class' => 'date',
                'rowspan' => 3, 
            ],
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'attribute' => 'timeIndexName',
            'format' => 'raw',
            'label' => '',
            'headerOptions' => [
                 'style'=>[
                    'width' => '15px',
                    'padding' => '4px 2px',
                ]
            ],
            'contentOptions' =>[
               'class' => 'time_index',
            ],
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'is_photograph',
            'label' => '',
            'value' => function($model) {
                return $model->is_photograph ? 
                        '<i class="glyphicon glyphicon-camera" style="color:#333"></i>':
                        '<i class="glyphicon glyphicon-camera" style="color:#ddd"></i>';
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '30px',
                    'padding' => '4px',
                ],
            ],
            'contentOptions' =>[
                //'class'=>'hidden-xs',
                'style'=>[
                    'font-size' => '18px',
                    'co1or' => '#333333',
                    'text-align' => 'center',
                    'vertical-align' => 'middle',
                    'padding' => '4px',
                ],
            ],
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'camera_count',
            'label' => '',
            'value' => function($model) {
                return $model->camera_count > 0 ? 
                        "<i class=\"glyphicon glyphicon-facetime-video\" style=\"color:#333\"></i>"
                            ."<span class=\"camera_count\">×{$model->camera_count}</span>" : 
                        "<i class=\"glyphicon glyphicon-facetime-video\" style=\"color:#ddd\"></i>";
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '50px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                //'class'=>'hidden-xs',
                'style'=>[
                    'font-size' => '18px',
                    'text-align' => 'center',
                    'vertical-align' => 'middle',
                    'padding' => '4px',
                ],
            ], 
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'content_type',
            'label' => '',
            'value' => function($model) {
                return "<span class=\"content_type\">{$model->content_type}</span>";
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '35px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                //'class'=>'hidden-xs',
                'style'=>[
                    'text-align' => 'center',
                    'vertical-align' => 'middle',
                    'padding' => '4px',
                ],
            ], 
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'start_time',
            'value' => function($model) {
                return !empty($model->start_time) ?  $model->start_time : '';
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '75px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                //'class'=>'hidden-xs',
                'style'=>[
                    'text-align' => 'center',
                    'vertical-align' => 'middle',
                    'padding' => '4px',
                ],
            ], 
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'lession_time course_id',
            'label' => Yii::t(null, '【{lession_time} × {course_id}】',[
                'lession_time' => Yii::t('app', 'Lession Time'),
                'course_id' => Yii::t('app', 'Course ID'),
            ]),
            'value' => function($model) {
                return !empty($model->course_id) ? "【{$model->lession_time} × {$model->course->name}】" : null;
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'min-width' => '100px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                'class' => 'course-name',
                'style'=>[
                    'vertical-align' => 'middle',
                    'padding' => '4px',
                ],
            ], 
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'remark',
            'value' => function($model) {
                return $model->remark;
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '255px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                'class' => 'course-name',
                'style'=>[
                    'vertical-align' => 'middle',
                    'padding' => '4px',
                ],
            ], 
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'teacher_id',
            'label' => Yii::t('app', 'Teacher'),
            'value' => function($model) {
                return !empty($model->teacher_id) ? $model->teacher->user->nickname : null;
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '85px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                'style'=>[
                    'vertical-align' => 'middle',
                    'padding' => '4px',
                ],
            ], 
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'label' => Yii::t('app', 'Contacter'),
            'value' => function($model) use($sceneBookUser) {
                if(isset($sceneBookUser[$model->id])){
                    foreach ($sceneBookUser[$model->id] as $bookUser) {
                        if($bookUser['role'] == 1 && $bookUser['is_primary'])
                            return $bookUser['nickname'];
                    }
                }
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '85px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                'style'=>[
                    'vertical-align' => 'middle',
                    'padding' => '4px',
                ],
            ], 
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'label' => Yii::t('app', 'Shoot Man'),
            'value' => function($model) use($sceneBookUser) {
                if(isset($sceneBookUser[$model->id])){
                    foreach ($sceneBookUser[$model->id] as $bookUser) {
                        if($bookUser['role'] == 2 && $bookUser['is_primary'])
                            return $bookUser['nickname'];
                    }
                }
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '85px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                'style'=>[
                    'vertical-align' => 'middle',
                    'padding' => '4px',
                ],
            ], 
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => Yii::t('app', 'Operating'),
            'buttons' => [
                'view' => function ($url, $model) {
                    /* @var $model SceneBook */
                    $url = $model->isNewRecord ? 
                        ['create', 'id' => $model->id, 'site_id' => $model->site_id, 'date' => $model->date, 'time_index' => $model->time_index, 'date_switch' => $model->date_switch] : 
                        ['view', 'id' => $model->id];
                    $options = [
                        'class' => 'btn btn-primary btn-sm',
                    ];
                    return Html::a('预约', $url, $options);
                },
            ],
            'headerOptions' => [
                'style' => [
                    'width' => '60px',
                    'padding' => '4px',
                ],
            ],
            'contentOptions' =>[
                'style' => [
                    'padding' => '8px 2px;',
                ],
            ],
            'template' => '{view}',
        ],
    ],
]); ?>    