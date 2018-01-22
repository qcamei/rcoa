<?php

use common\models\scene\SceneBook;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


//date('d')+1 明天预约时间
$dayTomorrow = date('Y-m-d H:i:s',strtotime("+1 days"));
//30天后预约时间
$dayEnd = date('Y-m-d H:i:s',strtotime("+31 days"));

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
                        '<i class="fa fa-camera" style="color:#333"></i>':
                        '<i class="fa fa-camera" style="color:#ddd"></i>';
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
                        "<i class=\"fa fa-video-camera\" style=\"color:#333\"></i>"
                            ."<span class=\"camera_count\">×{$model->camera_count}</span>" : 
                        "<i class=\"fa fa-video-camera\" style=\"color:#ddd\"></i>";
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
                'view' => function ($url, $model) use($dayTomorrow, $dayEnd) {
                    /* @var $model SceneBook */
                    //预约时间
                    $bookTime = date('Y-m-d H:i:s', strtotime($model->date.SceneBook::$startTimeIndexMap[$model->time_index]));
                    $isNew = $model->getIsNew();                        //新建任务
                    $isValid = $model->getIsValid();                    //非新建及锁定任务
                    $isBooking = $model->getIsBooking();                //是否预约中
                    $isAssign = $model->getIsAssign();                  //是否在【待指派】任务
                    $isStausShootIng = $model->getIsStausShootIng();    //是否在【待评价】任务
                    $isMe = $model->booker_id == Yii::$app->user->id;   //该预约是否为自己预约
                    $isTransfer = $model->is_transfer;                  //该预约是否为转让预约
                    //判断30天内的预约时段
                    if($dayTomorrow < $bookTime && $bookTime < $dayEnd){
                        $buttonName = $isNew  ? '<i class="fa fa-video-camera"></i>&nbsp;预约' : (!$isValid ? '预约中' : ($isTransfer ? '<i class="fa fa-refresh"></i>&nbsp;转让' : ($isAssign ? $model->getStatusName() : $model->getStatusName())));
                        $buttonClass = $isNew ? 'btn-primary' : (!$isValid ? 'btn-primary disabled' : ($isTransfer ? 'btn-primary' : ($isAssign ? 'btn-info' : 'btn-default')));
                    }else{
                        $buttonName = !$isNew ? ($isTransfer ? '<i class="fa fa-refresh"></i>&nbsp;转让' : $model->getStatusName()) : '<i class="fa fa-ban"></i>&nbsp;禁用';
                        $buttonClass = !$isNew ? ($isTransfer ? 'btn-primary' : 'btn-default') : 'btn-default disabled';
                    }
                    $url = $isNew ? 
                        ['create', 'id' => $model->id, 'site_id' => $model->site_id, 
                            'date' => $model->date, 'time_index' => $model->time_index, 
                            'date_switch' => $model->date_switch] : ['view', 'id' => $model->id];
                    $options = [
                        'class' => "btn $buttonClass btn-sm",
                    ];
                    return Html::a('<span class="'.($isMe ? 'isMe' : '').'"></span>'.$buttonName, $url, $options);
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
                    'padding' => '10px 2px;',
                ],
            ],
            'template' => '{view}',
        ],
    ],
]); ?>    