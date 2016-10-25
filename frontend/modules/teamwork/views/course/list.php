<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use wskeee\rbac\RbacName;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model CourseManage */
/* @var $dataProvider ActiveDataProvider */


$this->title = Yii::t('rcoa/teamwork', 'Item Deploy');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/teamwork', 'Items'),
                'url' => ['default/list'],
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa', 'Detail'),
                    'url' => ['default/view', 'id' => $model->id],
                    'template' => '<li class="course-name">{link}</li>',
                ],
                [
                    'label' => Yii::t('rcoa', 'Deploy').'：'.$model->itemChild->name,
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container item-manage-list has-title item-manage">
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->courseManages,
        ]),
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => Yii::t('rcoa/teamwork', 'Course ID').' ('.count($lessionTime).')',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->course->name;
                },
                'headerOptions' => [
                    'style' => [
                        'max-width' => '191px',
                        'min-width' => '84px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'course-name',
                    'style' => [
                        'max-width' => '191px', 
                        'max-width' => '84px', 
                    ],
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => Yii::t('rcoa/teamwork', 'Teacher'),
                'value' => function ($model){
                    /* @var $model CourseManage */
                    return $model->speakerTeacher->nickname;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '121px',
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => Yii::t('rcoa/teamwork', 'Lession Time').'('.  array_sum($lessionTime).')',
                'format' => 'raw',
                'value' => function($model){
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
                'format' => 'raw',
                'value' => function($model){
                    /* @var $model CourseManage */
                    return $model->des;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '604px',
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
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
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['default/view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?php
            /**
             * 添加课程 按钮显示必须满足以下条件：
             * 1、必须是【队长】  or 【项目管理员】
             */
            if($twTool->getIsAuthority('is_leader', 'Y') /*|| Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)*/)
                echo Html::a(Yii::t('rcoa/teamwork', 'Create Course'), ['create','project_id' => $model->id], 
                ['class' => 'btn btn-primary']);
        ?>
    </div>
</div>

<?php
    TwAsset::register($this);
?>