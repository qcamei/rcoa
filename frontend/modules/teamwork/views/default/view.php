<?php

use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TwAsset;
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
        'statusName' => $statusName,
    ]) ?>
    
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
        <?php
            /**
             * 编辑 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $model->isLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 配置 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             */
            if($model->getIsNormal() && $model->isLeader())    
                echo Html::a('配置', ['/teamwork/course/index'], ['class' => 'btn btn-primary']).' ';
            /**
             * 课程 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             */
            if($model->getIsNormal())
                echo Html::a('课程', ['/teamwork/course/create', 'project_id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 完成 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $model->isLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('完成', ['carry-out', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
            /**
             * 暂停 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $model->isLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('暂停', ['time-out', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
        ?>
    </div>
</div>

<?php
    TwAsset::register($this);
?>