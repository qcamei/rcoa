<?php

use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TwAsset;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ItemManage */

$this->title = Yii::t('rcoa/teamwork', 'Item View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Item Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="title">
    <div class="container">
        <?= $this->title ?>
    </div>
</div>

<div class="container item-manage-view item-manage has-title">
    
    <?= $this->render('_form_detai', [
        'model' => $model,
        //'statusName' => $statusName,
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
                'label' => '课程名称 ('.count($model->courseManages).')',
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->course->name;
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '主讲讲师',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->speakerTeacher->nickname;
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                //array_sum()返回数组中所有值的和
                'label' => '学时 ('.array_sum($model->getCourseLessionTimesSum()).')', 
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->lession_time;
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '课程描述',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->des;
                },
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
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $model->getIsLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 配置 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             */
            if($model->getIsNormal() && $model->getIsLeader())    
                echo Html::a('配置', ['/teamwork/course/list', 'project_id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 课程 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             */
            if($model->getIsNormal())
                echo Html::a('课程', ['/teamwork/course/index', 'project_id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 完成 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $model->getIsLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('完成', ['carry-out', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
            /**
             * 暂停 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $model->getIsLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('暂停', ['time-out', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
            /**
             * 恢复 按钮显示必须满足以下条件：
             * 1、必须是状态为【暂停】
             * 2、必去是【队长】
             * 3、创建者是自己
             */
            if($model->getIsTimeOut() && $model->getIsLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('恢复', ['normal', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
        ?>
    </div>
</div>

<?php
    TwAsset::register($this);
?>