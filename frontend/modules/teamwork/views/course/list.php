<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model CourseManage */
/* @var $dataProvider ActiveDataProvider */


$this->title = Yii::t('rcoa/teamwork', 'Item Manage');
$this->params['breadcrumbs'][] = $this->title;

!empty($allModels) ? : $model->project_id = $project_id;
?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/teamwork', 'Item Manages'),
                'url' => ['list'],
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa/teamwork', 'Item View'),
                    'url' => ['view', 'id' => $model->project_id],
                ],
                [
                    'label' => $this->title.'：'.$model->project->itemChild->name,
                    'class' => 'active',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container item-manage-list has-title item-manage">
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $allModels,
        ]),
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '课程名称 ('.count($model->project->courseManages).')',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->course->name;
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '主讲讲师',
                'value' => function ($model){
                    /* @var $model CourseManage */
                    return $model->speakerTeacher->nickname;
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '学时 ('.$lessionTime.')',
                'format' => 'raw',
                'value' => function($model){
                        /* @var $model CourseManage */
                        return $model->lession_time;
                    }
                ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '课程描述',
                'format' => 'raw',
                'value' => function($model){
                    /* @var $model CourseManage */
                    return $model->des;
                }
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemActBtnCol',
                'label' => '操作',
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
        <?= Html::a(Yii::t('rcoa', 'Back'), ['default/view', 'id' => $model->project_id], ['class' => 'btn btn-default']) ?>
        <?php
            /**
             * 添加课程 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             */
            if($model->project->getIsNormal() && $twTool->getIsLeader())
                echo Html::a(Yii::t('rcoa/teamwork', 'Create Course Manage'), ['create','project_id' => $model->project_id], 
                ['class' => 'btn btn-primary']);
        ?>
    </div>
</div>

<?php
    TwAsset::register($this);
?>