<?php

use common\models\need\NeedTask;
use common\models\need\searchs\NeedTaskSearch;
use frontend\modules\need\assets\ModuleAssets;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;


/* @var $this View */
/* @var $searchModel NeedTaskSearch */
/* @var $dataProvider ActiveDataProvider */

ModuleAssets::register($this);

$this->title = Yii::t('app', 'Need Tasks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container need-task-index">
   
    <?= $this->render('_search', [
        'model' => $searchModel,
    ]) ?>
    
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
                    return $model->level ? '<i class="fa fa-bolt">' : '';
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'business_id',
                'label' => Yii::t('app', 'Business ID'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'layer_id',
                'label' => Yii::t('app', 'Layer ID'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'profession_id',
                'label' => Yii::t('app', 'Profession ID'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'course_id',
                'label' => Yii::t('app', 'Course ID'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'task_name',
                'label' => Yii::t('app', 'Task Name'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'need_time',
                'label' => Yii::t('app', 'Need Time'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'finish_time',
                'label' => Yii::t('app', 'Finish Time'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'created_by',
                'label' => Yii::t('app', 'Created By'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'receive_by',
                'label' => Yii::t('app', 'Receive By'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'plan_content_cost',
                'label' => Yii::t('app', 'Plan Content Cost'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'reality_content_cost',
                'label' => Yii::t('app', 'Reality Content Cost'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
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
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
           
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
