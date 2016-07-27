<?php

use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TwAsset;
use wskeee\rbac\RbacName;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model ItemManage */

$this->title = Yii::t('rcoa/teamwork', 'Item View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Item Manages'), 'url' => ['list']];
$this->params['breadcrumbs'] = $this->title;
?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/teamwork', 'Items'),
                'url' => ['list'],
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa', 'Detail').'：'.$model->itemChild->name,
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container item-manage-view item-manage has-title">
    
    <?= $this->render('_form_detai', [
        'model' => $model,
    ]) ?>
    
    <h4>课程配置信息</h4>
     <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->courseManages,
        ]),
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => Yii::t('rcoa/teamwork', 'Course ID').' ('.count($model->courseManages).')',
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->course->name;
                },
                'headerOptions' => [
                    'style' => [
                        'max-width' => '214px',
                        'min-width' => '84px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'course-name',
                    'style' => [
                        'max-width' => '214px', 
                        'max-width' => '84px', 
                    ],
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => Yii::t('rcoa/teamwork', 'Teacher'),
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->speakerTeacher->nickname;
                },
                'headerOptions' => [
                    'style' => [
                        'max-width' => '115px',
                    ],
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                //array_sum()返回数组中所有值的和
                'label' => Yii::t('rcoa/teamwork', 'Lession Time').'('.$lessionTime.')', 
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->lession_time;
                },
                'headerOptions' => [
                    'style' => [
                        //'max-width' => '191px',
                        'width' => '84px',
                    ],
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => Yii::t('rcoa/teamwork', 'Des'),
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->des;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'max-width' => '740px',
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                ],
            ],
            
        ],
    ]); ?>
    
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['list'], ['class' => 'btn btn-default']) ?>
        <?php
            /**
             * 编辑 按钮显示必须满足以下条件：
             * 1、必须是状态为【在建】
             * 2、必须是【队长】 or 【项目管理员】
             */
            if($model->getIsNormal() && ($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
                echo Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 配置 按钮显示必须满足以下条件：
             * 1、必须是状态为【在建】
             * 2、必须是【队长】 or 【项目管理员】
             */
            if($model->getIsNormal() && $twTool->getIsLeader())    
                echo Html::a('配置', ['/teamwork/course/list', 'project_id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 课程 按钮显示必须满足以下条件：
             * 1、必须是状态为【在建】
             */
            if($model->getIsNormal())
                echo Html::a('课程', ['/teamwork/course/index', 'project_id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 完成 按钮显示必须满足以下条件：
             * 1、必须是状态为【在建】
             * 2、必须是【队长】 or 【项目管理员】
             
            if($model->getIsNormal() && ($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
                echo Html::a('完成', ['carry-out', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
            */
            /**
             * 暂停 按钮显示必须满足以下条件：
             * 1、必须是状态为【在建】
             * 2、必须是【队长】 or 【项目管理员】
             
            if($model->getIsNormal() && ($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
                echo Html::a('暂停', ['time-out', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
             */
            /**
             * 恢复 按钮显示必须满足以下条件：
             * 1、必须是状态为【在建】
             * 2、必去是【队长】 or 【项目管理员】
             
            if($model->getIsTimeOut() && ($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
                echo Html::a('恢复', ['normal', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
             */
        ?>
    </div>
</div>

<?php
    TwAsset::register($this);
?>