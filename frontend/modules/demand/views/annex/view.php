<?php

use common\models\demand\DemandTaskAnnex;
use frontend\modules\demand\assets\DemandAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model DemandTaskAnnex */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Task Annexes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/layouts/_title', [
    'params' => ['task/index'],
    'title' => Yii::t('rcoa/demand', 'Demand View'),
]) ?>

<div class="container demand-task-annex-view has-title">
    <iframe class="col-lg-12 col-md-12 col-sm-12 col-xs-12" src="http://eezxyl.gzedu.com?furl=http://eefile.download.eenet.com<?= $model->path?>" style="height: 550px;padding: 0px;">
    </iframe>
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['task/view', 'id' => $model->task_id], ['class' => 'btn btn-default',/*'onclick'=>'history.go(-1)'*/]) ?>
    </div>
</div>

<?php
    DemandAssets::register($this);
?>
