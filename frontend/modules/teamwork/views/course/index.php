<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/teamwork', 'Course Manages');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container course-manage-index has-title">
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
           [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '项目类型',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->project->itemType->name;
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '项目名称',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->project->item->name;
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '子项目名称',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->project->itemChild->name;
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '课程名称',
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return Html::a($model->course->name, ['view','id' => $model->id], [
                            'style' => 'color:#000',
                        ]);
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '制作团队',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->project->teamMember->team->name;
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '进度',
                /*'value'=> function($model){
                    /* @var $model CourseManage
                    return $model->project->teamMember->team->name;
                },*/
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
                        'width' => '185px',
                    ]
                ],
            ],            
        ],
    ]); ?>
</div>

<?= $this->render('/default/_footer') ?>

<?php
    TwAsset::register($this);
?>