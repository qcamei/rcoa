<?php

use common\models\shoot\searchs\ShootBookdetailSearch;
use common\models\shoot\ShootBookdetail;
use frontend\modules\shoot\components\ShootAppraiseStar;
use frontend\modules\shoot\ShootAsset;
use kartik\widgets\DatePicker;
use wskeee\rbac\RbacName;
use wskeee\utils\DateUtil;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ShootBookdetailSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa', 'Shoot Bookdetails');
?>
<div class="container shoot-bookdetail-index bookdetail-list" style="padding:0;">
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped'],
        'columns' => [
            [
                'class' => 'frontend\modules\shoot\components\ShootBookdetailListWeekTd',
                'value' => function($model) {
                    return date('Y/m/d ', $model->book_time) .Yii::t('rcoa', 'Week ' . date('D', $model->book_time));
                },
                'label' => '时间',
                'contentOptions' =>[
                    'rowspan' => 3, 
                    'style'=>[
                        'vertical-align' => 'middle',
                        'width' => '95px'
                    ]
                ] 
            ],
            [
                'class' => 'frontend\modules\shoot\components\ShootBookdetailListTd',
                'attribute' => 'timeIndexName',
                'label' => '',
                'contentOptions' =>[
                   'style'=>[
                        'vertical-align' => 'middle',
                        'width' => '24px',
                        'padding' => '4px',
                    ]
                ] 
            ],
            [
                'class' => 'frontend\modules\shoot\components\ShootBookdetailListTd',
                'attribute' => 'shoot_mode',
                'label' => '',
                'contentOptions' =>[
                   'class'=>'hidden-xs',
                   'style'=>[
                        'vertical-align' => 'middle',
                        'width' => '29px',
                        'padding' => '4px',
                    ],
                ],
                'headerOptions'=>[
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                ],
                'content' => function($model,$key,$index,$e)
                {
                   /*@var $model ShootBookdetail*/ 
                    if(!$model->getIsValid())return '';
                    return '<span class="rcoa-icon rcoa-icon-'.($model->shoot_mode == ShootBookdetail::SHOOT_MODE_SD ? 'sd' : 'hd').'"/>' ;
                }
            ],
            [
                'class' => 'frontend\modules\shoot\components\ShootBookdetailListTd',
                'attribute' => 'photograph',
                'label' => '',
                'headerOptions'=>[
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                    'style'=>[
                        'vertical-align' => 'middle',
                        'width' => '29px',
                        'padding' => '4px',
                    ],
                ], 
                'content' => function($model,$key,$index,$e)
                {
                   return $model->photograph == 1 ? '<span class="rcoa-icon rcoa-icon-camera"/>' : "";
                }
            ],
            [
                'class' => 'frontend\modules\shoot\components\ShootBookdetailListTd',
                'label' => '【课程名 x 课时】',
                 'contentOptions' =>[
                    
                ], 
                'content' => function($model,$key,$index,$e)
                {
                    /* @var $model ShootBookdetail */
                    if(!$model->getIsValid())
                        return '';
                        $str = '【'.$model->getFwCourse()->name;
                        $time = ' x '.$model->lession_time.'】';
                        /*判断 @var $str字符长度并截取*/
                        if(strlen($str) > 36){
                           $str = substr($str,0,36); 
                           return $str.'...'.$time;
                        }
                    return $str.$time;
                }
            ],
            [
                'class' => 'frontend\modules\shoot\components\ShootBookdetailListTd',
                'label' => '【老 师 / 接洽人 / 预约人 / 摄影师】',
                'headerOptions'=>[
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                    'style'=> [
                        'width' => '305px',
                    ],
                ], 
                'content' => function($model,$key,$index,$e)
                {
                    if($model->getIsBooking())
                        return '【'. DateUtil::intToTime($model->getBookTimeRemaining(),2).'】后解锁';
                    /* @var $model ShootBookdetail */
                    if(!$model->getIsValid())
                        return '';
                    /** 获取评价结果 */
                    $appInfo = $model->getAppraiseInfo();
                    //生成【接洽人】【摄影师】评价图标
                    $contacterGood = $appInfo[RbacName::ROLE_CONTACT]['hasDo'] ? ShootAppraiseStar::widget([
                        'value'=>$appInfo[RbacName::ROLE_CONTACT]['sum']/$appInfo[RbacName::ROLE_CONTACT]['all']
                            ]) : "" ;
                    $shootManGood = $appInfo[RbacName::ROLE_SHOOT_MAN]['hasDo'] ?  ShootAppraiseStar::widget([
                        'value'=>$appInfo[RbacName::ROLE_SHOOT_MAN]['sum']/$appInfo[RbacName::ROLE_SHOOT_MAN]['all']
                            ]) : "";
                    /* @var $model ShootBookdetail */
                    $teacherName = isset($model->u_teacher) ? $model->teacher->nickname : '空';
                    $contacterName = isset($model->u_contacter) ? $model->contacter->nickname : '空';
                    $bookerName = isset($model->u_booker) ? $model->booker->nickname : '空';
                    $shootManName = isset($model->u_shoot_man) ? $model->shootMan->nickname : '空';
                    return '【'.$teacherName.' / '.$contacterGood.$contacterName .' / '.$bookerName.' / '.$shootManGood.$shootManName.'】';
                }
            ],
            [
                'class' => 'frontend\modules\shoot\components\ShootBookdetailListTd',
                'label' => '【状态】',
                'headerOptions'=>[
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                    'style'=> [
                        'width' => '90px',
                    ],
                ], 
                'content' => function($model,$key,$index,$e)
                {
                    /* @var $model ShootBookdetail */
                    if($model->getIsNew())return '';
                    return '【'.$model->getStatusName().'】';
                }
            ],
            [
                'class' => 'frontend\modules\shoot\components\ShootBookdetailActBtnCol',
                'label' => '操作',
                'contentOptions' =>[
                    'style'=> [
                        'width' => '90px',
                        'padding' =>'4px',
                    ]
                ],
            ],
        ],
    ]);
    ?>
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="btn btn-default" style="padding: 0px">
                <?= Html::dropDownList('site', 0, ['6-5摄']) ?>
            </div>
            <div  class="btn btn-default" style="padding: 0px;width: 85px">
                <?=
                DatePicker::widget([
                    'name' => 'check_issue_date',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => date('Y/m'),
                    'options' => ['placeholder' => 'Select issue date ...'],
                    'pluginOptions' => [
                        'format' => 'yyyy/m',
                        'todayHighlight' => true,
                        'minViewMode' => 1,
                    ]
                ]);
                ?>
            </div>
            <?= 
                Html::a('<', 
                        Url::to(['/shoot/bookdetail','date'=>$prevWeek]),['class'=>'btn btn-default']);
            ?>
             <?= 
                Html::a('>', 
                        Url::to(['/shoot/bookdetail','date'=>$nextWeek]),['class'=>'btn btn-default']);
            ?>
        </div>
    </div>
</div>
<?php
    ShootAsset::register($this);
?>