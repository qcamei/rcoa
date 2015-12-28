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
                'format' => 'raw',
                'value' => function($model) {
                    return date('m/d ', $model->book_time).'</br>' .Yii::t('rcoa', 'Week ' . date('D', $model->book_time));
                },
                'label' => '时间',
                'contentOptions' =>[
                    'rowspan' => 3, 
                    'style'=>[
                        'vertical-align' => 'middle',
                    ]
                ],
                'headerOptions' => [
                     'style'=>[
                        'width' => '60px'
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
                        'padding' => '4px',
                    ]
                ],
                'headerOptions' => [
                     'style'=>[
                        'width' => '29px'
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
                        'padding' => '4px',
                    ],
                ],
                'headerOptions'=>[
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '29px',
                    ]
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
                    'style' => [
                        'width' => '24px',
                    ]
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                    'style'=>[
                        'vertical-align' => 'middle',
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
                'headerOptions'=>[
                    'style'=>[
                       'min-width' => '100px',
                    ],
                ],
                
                'content' => function($model,$key,$index,$e)
                {
                    if($model->getIsBooking())
                        return '【'. DateUtil::intToTime($model->getBookTimeRemaining(),2).'】后解锁';
                    /* @var $model ShootBookdetail */
                    if(!$model->getIsValid())
                        return '';
                        $str = '【'.$model->getFwCourse()->name;
                        $time = ' x '.$model->lession_time.'】';
                    return '<p class="course-name">'.$str.$time.'</p>';
                }
            ],
            [
                'class' => 'frontend\modules\shoot\components\ShootBookdetailListTd',
                'label' => '【老 师 / 接洽人 / 预约人 / 摄影师】',
                'headerOptions'=>[
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style'=>[
                        'width' => '305px',
                    ]
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                    'style'=> [
                        'white-space' => 'nowrap',
                    ],
                ], 
                'content' => function($model,$key,$index,$e)
                {
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
                    'style'=> [
                        'width' => '90px',
                    ]
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                    'style'=> [
                        'white-space' => 'nowrap',
                    ],
                ], 
                'content' => function($model,$key,$index,$e)
                {
                    /* @var $model ShootBookdetail */
                    if($model->getIsNew())return '';
                    /** 设置时间限制　*/
                    if(!$model->getIsStatusCompleted() && (time() - $model->book_time > $model::STATUS_BREAK_PROMISE_TIMEOUT)){
                            $model->status = $model::STATUS_BREAK_PROMISE;
                            $model->save();
                    }
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
                'headerOptions'=>[
                    'style'=> [
                        'width' => '90px',
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
                <?= Html::dropDownList('site', $site, $sites, ['onchange'=>'siteDropDownListChange($(this).val())'])?> 
            </div>
            <div  class="btn btn-default" style="padding: 0px;width: 85px">
                <?=
                DatePicker::widget([
                    'name' => 'check_issue_date',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => date('Y/m',strtotime($date)),
                    'readonly' => true,
                    'options' => [
                        'placeholder' => 'Select issue date ...',
                        'onchange'=>'dateChange($(this).val())',
                        ],
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
                        Url::to(['/shoot/bookdetail','date'=>$prevWeek, 'site'=>$site]),['class'=>'btn btn-default']);
            ?>
             <?= 
                Html::a('>', 
                        Url::to(['/shoot/bookdetail','date'=>$nextWeek, 'site'=>$site]),['class'=>'btn btn-default']);
            ?>
        </div>
    </div>
</div>

<?php  
    $reflashUrl = Yii::$app->urlManager->createAbsoluteUrl(['/shoot/bookdetail/']);
    $js =
<<<JS
    var reflashUrl = "$reflashUrl";
    var refdate = "$date";
    var refsite = "$site";
    function siteDropDownListChange(value)
    {  
        location.href = reflashUrl+'?date='+refdate+'&site='+value;
    }
    function dateChange(value)
    {
         value += '/01';
         location.href = reflashUrl+'?date='+value+'&site='+refsite;
    }
JS;
    ShootAsset::register($this);
    $this->registerJs($js, View::POS_HEAD);
?> 
<?php
    ShootAsset::register($this);
?>