<?php

use common\models\need\NeedTask;
use common\models\need\NeedTaskUser;
use common\models\need\searchs\NeedTaskUserSearch;
use kartik\slider\Slider;
use wskeee\rbac\components\ResourceHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this View */
/* @var $model NeedTask */
/* @var $searchModel NeedTaskUserSearch */
/* @var $dataProvider ActiveDataProvider */

//$this->title = Yii::t('app', 'Need Task Users');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="need-task-user-index">
    <div class="col-xs-12 frame">
    
        <div class="col-xs-12 title">
            <i class="fa fa-users"></i>
            <span><?= Yii::t('app', '开发人员') ?></span>
            <div class="btngroup">
                <?php
                    $conditions = $model->receive_by == Yii::$app->user->id && !($model->getIsFinished() || $model->is_del);
                    echo ResourceHelper::a(Yii::t('app', 'Add'), ['user/create', 'need_task_id' => $model->id], [
                        'class' => 'btn btn-sm btn-success',
                        'onclick' => 'showModal($(this)); return false;',
                    ], $conditions);
                ?>
            </div>
        </div>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'summaryOptions' => ['class' => 'hidden'],
            'pager' => [
                'options' => ['class' => 'hidden']
            ],
            'tableOptions' => ['class' => 'table table-list table-bordered table-frame'],
            'columns' => [
                [
                    //'attribute' => 'user_id',
                    'label' => Yii::t('app', 'Name'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model NeedTaskUser */
                        return !empty($model->user_id) ? $model->user->nickname : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'rows',
                        ],
                        'style' => [
                            'width' => '100px',
                            'padding' => '8px 4px',
                        ],
                    ],
                    'contentOptions' =>[
                        'class' => [
                            //'td' => 'hidden-xs'
                        ],
                        'style' => [
                            'padding' => '8px 4px',
                            'color' => '#666',
                            'white-space' => 'nowrap',
                        ],
                    ],
                ],
                [
                    //'attribute' => 'performance_percent',
                    'label' => Yii::t('app', 'Performance Percent'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model NeedTaskUser */
                        return Slider::widget([
                                'id' => 'w' . $model->id . '-slider',
                                'name' => 'NeedTaskUser[performance_percent]',
                                'value' => $model->performance_percent,
                                'sliderColor' => Slider::TYPE_SUCCESS,
                                'handleColor' => Slider::TYPE_SUCCESS,
                                'options' => [
                                    'disabled' => $model->user_id != $model->needTask->receive_by && 
                                                    !($model->needTask->getIsFinished() || $model->needTask->is_del) &&
                                                    $model->needTask->receive_by == Yii::$app->user->id ? false : true,
                                    'style' => ['width' => '93%'],
                                ],
                                'pluginOptions'=>[
                                    'min' => 0.00,
                                    'max'=> 1,
                                    'precision' => 2,
                                    'handle' => 'square',
                                    'step' => 0.01,
                                    //'tooltip'=>'always',
                                    'formatter' => new JsExpression("function(val) {
                                        return Math.round(val * 100) + '%';
                                    }"),
                                ],
                                'pluginEvents' => [
                                    'slideStop' => "function(){ updataDeveloper($(this)); }"
                                ],
                            ]) . '<span class="stamp" style="margin-top: 5px;">'. $model->performance_percent * 100 . '%</span>';
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th' => 'rows',
                        ],
                        'style' => [
                            'width' => '100%',
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
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '',
                    'buttons' => [
                        'delete' => function ($url, $model){
                            /* @var $model NeedTaskUser */
                            $conditions = $model->user_id != $model->needTask->receive_by 
                                && !($model->needTask->getIsFinished() || $model->needTask->is_del) 
                                && $model->needTask->receive_by == Yii::$app->user->id;
                            return ResourceHelper::a('<i class="glyphicon glyphicon-trash"></i>', ['user/delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger btn-sm',
                                'onclick' => 'deleteDeveloper($(this)); return false;'
                            ], $conditions);
                            
                        },
                    ],
                    'headerOptions' => [
                        'class' => [
                            'th' => 'rows',
                        ],
                        'style' => [
                            'width' => '55px',
                            'padding' => '4px 4px;',
                        ],
                    ],
                    'contentOptions' =>[
                        'style' => [
                            'width' => '55px',
                            'padding' => '4px 4px;',
                            'text-align' => 'center',
                        ],
                    ],
                    'template' => '{delete}',
                ],
            ],
        ]); ?>
        
    </div>    
</div>
