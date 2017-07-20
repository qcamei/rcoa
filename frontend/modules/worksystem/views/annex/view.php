<?php

use common\models\worksystem\WorksystemAnnex;
use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model WorksystemAnnex */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Annexes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/layouts/_title', [
    'params' => ['index', 
        'create_by' => Yii::$app->user->id, 
        'producer' => Yii::$app->user->id, 
        'assign_people' => Yii::$app->user->id,
        'status' => WorksystemTask::STATUS_DEFAULT,
        'mark' => false,
    ],
    'title' => $this->title,
]) ?>

<div class="container worksystem worksystem-annex-view has-title">
    <iframe class="col-lg-12 col-md-12 col-sm-12 col-xs-12" src="http://eezxyl.gzedu.com?furl=http://eefile.download.eenet.com<?= $model->path?>" style="height: 550px;padding: 0px;">
    </iframe>
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['task/view', 'id' => $model->worksystem_task_id], ['class' => 'btn btn-default',/*'onclick'=>'history.go(-1)'*/]) ?>
    </div>
</div>

<?php
    WorksystemAssets::register($this);
?>