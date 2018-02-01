<?php

use common\models\Holiday;
use common\models\scene\SceneBook;
use frontend\modules\scene\assets\SceneAsset;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

//date('d')+1 明天预约时间
$dayTomorrow = date('Y-m-d H:i:s',strtotime("+1 days"));
//30天后预约时间
$dayEnd = date('Y-m-d H:i:s',strtotime("+31 days"));
//节假日颜色
$holidayColourMap = [1 => 'red', 2 => 'yellow', 3 => 'green'];

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
            'value' => function($model) use($holidays, $holidayColourMap){
                $holiday= '';$content = '';
                if(isset($holidays[$model->date])){
                    $first = reset($holidays[$model->date]);
                    foreach ($holidays[$model->date] as $holiday)
                        $content .= "<p>{$holiday['name']}(".Holiday::TYPE_NAME_MAP[$holiday['type']].")</p>";
                    $holiday = "<a class=\"holiday img-circle {$holidayColourMap[$first['type']]}\" role=\"button\" data-content=\"{$content}\">".Holiday::TYPE_NAME_MAP[$first['type']]."</a>";
                }
                $date = date('m/d ', strtotime($model->date)).'</br>' .Yii::t('rcoa', 'Week ' . date('D', strtotime($model->date)));
                return '<div style="position: relative">'.$date.$holiday.'</div>';
            },
            'headerOptions' => [
                'style'=>[
                    'width' => '50px',
                    'padding' => '4px 2px',
                ]
            ],
            'contentOptions' =>[
                'class' => 'date',
                'rowspan' => 3, 
                'style' => [
                    'padding' => '4px ',
                ],
            ],
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'attribute' => 'timeIndexName',
            'format' => 'raw',
            'label' => '',
            'headerOptions' => [
                'style'=>[
                    'width' => '20px',
                    'padding' => '4px 2px',
                ]
            ],
            'contentOptions' =>[
               'class' => 'time_index',
                'style'=>[
                    'padding' => '4px 2px',
                ]
            ],
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'is_photograph',
            'label' => '',
            'value' => function($model) {
                return $model->getIsValid() ? ($model->is_photograph ? 
                        '<i class="fa fa-camera Yes"></i>':
                        '<i class="fa fa-camera No"></i>') : '';
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '25px',
                    'padding' => '4px 2px',
                ],
            ],
            'contentOptions' =>[
                //'class'=>'hidden-xs',
                'style'=>[
                    'font-size' => '18px',
                    'co1or' => '#333333',
                    'text-align' => 'center',
                    'vertical-align' => 'middle',
                    'padding' => '4px 2px',
                ],
            ],
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'camera_count',
            'label' => '',
            'value' => function($model) {
                return $model->getIsValid() ? ($model->camera_count > 0 ? 
                        "<i class=\"fa fa-video-camera Yes\"></i>"
                            ."<span class=\"camera_count\">×{$model->camera_count}</span>" : 
                        "<i class=\"fa fa-video-camera No\"></i>") : '';
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '40px',
                    'padding' => '4px 2px',
                ]
            ],
            'contentOptions' =>[
                //'class'=>'hidden-xs',
                'style'=>[
                    'font-size' => '18px',
                    'text-align' => 'left',
                    'vertical-align' => 'middle',
                    'padding' => '4px 2px',
                ],
            ], 
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'content_type',
            'label' => '',
            'value' => function($model) {
                return $model->getIsValid() ? "<span class=\"content_type\">{$model->content_type}</span>" : '';
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '35px',
                    'padding' => '4px 2px',
                ]
            ],
            'contentOptions' =>[
                //'class'=>'hidden-xs',
                'style'=>[
                    'text-align' => 'center',
                    'vertical-align' => 'middle',
                    'padding' => '4px 2px',
                ],
            ], 
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'start_time',
            'value' => function($model) {
                return $model->getIsValid() ?  $model->start_time : '';
            },
            'headerOptions'=>[
                'class'=>[
                    'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '75px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                'class'=>'hidden-xs',
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
                return $model->getIsValid() ? (!empty($model->course_id) ? "【{$model->lession_time} × {$model->course->name}】" : null) : '';
            },
            'headerOptions'=>[
                'class'=>[
                    //'th'=>'hidden-xs',
                ],
                'style' => [
                    'min-width' => '100px',
                    'padding' => '4px 2px',
                ]
            ],
            'contentOptions' =>[
                'class' => 'course-name',
                'style'=>[
                    'vertical-align' => 'middle',
                    'padding' => '4px 2px',
                ],
            ], 
        ],
        [
            //'class' => 'frontend\modules\scene\components\SceneBookList',
            'format' => 'raw',
            'attribute' => 'remark',
            'value' => function($model) {
                return $model->getIsValid() ? $model->remark :  '';
            },
            'headerOptions'=>[
                'class'=>[
                    'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '255px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                'class' => 'hidden-xs',
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
                return $model->getIsValid() ? (!empty($model->teacher_id) ? $model->teacher->user->nickname : null) : '';
            },
            'headerOptions'=>[
                'class'=>[
                    'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '85px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                'class' => 'hidden-xs',
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
                if($model->getIsValid()){
                    if(isset($sceneBookUser[$model->id])){
                        foreach ($sceneBookUser[$model->id] as $bookUser) {
                            if($bookUser['role'] == 1 && $bookUser['is_primary'])
                                return $bookUser['nickname'].'<div class="star" data-score="'.$bookUser['score'].'"></div>';
                        }
                    }
                }else {
                    return '';
                }
            },
            'headerOptions'=>[
                'class'=>[
                    'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '85px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                'class' => 'hidden-xs',
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
                if($model->getIsValid()){
                    if(isset($sceneBookUser[$model->id])){
                        foreach ($sceneBookUser[$model->id] as $bookUser) {
                            if($bookUser['role'] == 2 && $bookUser['is_primary'])
                                return $bookUser['nickname'].'<div class="star" data-score="'.$bookUser['score'].'"></div>';
                        }
                    }
                }else{
                    return '';
                }
            },
            'headerOptions'=>[
                'class'=>[
                    'th'=>'hidden-xs',
                ],
                'style' => [
                    'width' => '85px',
                    'padding' => '4px',
                ]
            ],
            'contentOptions' =>[
                'class' => 'hidden-xs',
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
                'view' => function ($url, $model) use($dayTomorrow, $dayEnd, $sceneBookUser, $siteManage) {
                    $bookUsers = [];
                    if(isset($sceneBookUser[$model->id])){
                        foreach ($sceneBookUser[$model->id] as $book_user) {
                            if($book_user['is_primary']){
                                $bookUsers[$book_user['book_id']][] = $book_user['user_id'];
                            }
                        }
                    }
                    /* @var $model SceneBook */
                    //预约时间
                    $bookTime = date('Y-m-d H:i:s', strtotime($model->date.SceneBook::$startTimeIndexMap[$model->time_index]));
                    $isNew = $model->getIsNew();                        //新建任务
                    $isValid = $model->getIsValid();                    //非新建及锁定任务
                    $isBooking = $model->getIsBooking();                //是否预约中
                    $isAssign = $model->getIsAssign();                  //是否在【待指派】任务
                    $isStausShootIng = $model->getIsStausShootIng();    //是否在【待评价】任务
                    $isAppraise = $model->getIsAppraise();              //是否在【评价中】任务
                    $isBreakPromise = $model->getIsStatusBreakPromise();//是否在【已失约】任务
                    //该预约是否为自己的操作
                    $isMe = $model->booker_id == Yii::$app->user->id || (isset($bookUsers[$model->id]) && in_array(Yii::$app->user->id, $bookUsers[$model->id]));
                    $isTransfer = $model->is_transfer;                  //该预约是否为转让预约
                    $isPublishSite = $model->sceneSite->is_publish;     //是否发布该场地
                    //场次是否禁用
                    $isDisable = isset($siteManage[$model->date][$model->time_index]) && $siteManage[$model->date][$model->time_index];
                    //判断30天内的预约时段
                    if(($dayTomorrow < $bookTime && $bookTime < $dayEnd) && $isPublishSite){
                        $buttonName = $isDisable ? '<i class="fa fa-ban"></i>　禁' : ($isNew  ? '预　约' : (!$isValid ? '预约中' : ($isTransfer && $bookTime > date('Y-m-d H:i:s', time()) ? '<i class="fa fa-refresh"></i>转让' : ($isAssign ? $model->getStatusName() : $model->getStatusName()))));
                        $buttonClass = $isDisable ? 'btn-default disabled' : ($isNew ? 'btn-primary' : (!$isValid ? 'btn-primary disabled' : ($isTransfer && $bookTime > date('Y-m-d H:i:s', time()) ? 'btn-primary' : ($isAssign ? 'btn-success' : ($isStausShootIng || $isAppraise ? 'btn-info' : ($isBreakPromise ? 'btn-danger' : 'btn-default'))))));
                    }else{
                        $buttonName = !$isNew ? ($isTransfer && $bookTime > date('Y-m-d H:i:s', time()) ? '<i class="fa fa-refresh"></i>转让' : $model->getStatusName()) : '<i class="fa fa-ban"></i>　禁';
                        $buttonClass = !$isNew ?  ($isTransfer && $bookTime > date('Y-m-d H:i:s', time()) ? 'btn-primary' : ($isBreakPromise ? 'btn-danger' : ($isStausShootIng || $isAppraise ? 'btn-info' : 'btn-default'))) : 'btn-default disabled';
                    }
                    $url = $isNew ? 
                        ['create', 'id' => $model->id, 'site_id' => $model->site_id, 
                            'date' => $model->date, 'time_index' => $model->time_index, 
                            'date_switch' => $model->date_switch] : ['view', 'id' => $model->id];
                    $options = [
                        'class' => "btn $buttonClass btn-sm",
                        'target' => $isNew ? : '_blank'
                    ];
                    return Html::a('<span class="'.($isMe ? 'isMe' : '').'"></span>'.$buttonName, $url, $options);
                },
            ],
            'headerOptions' => [
                'style' => [
                    'width' => '65px',
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

<?php

$js = 
<<<JS
    
    $('.star').raty({
        number: 3,
        score: function() {
            return Math.floor($(this).attr('data-score'));
        },
        path: '/filedata/scene/icons',
        readOnly: true,
    });
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>