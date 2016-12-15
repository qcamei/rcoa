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

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/demand', 'Demand Tasks'),
                'url' => ['task/index'],
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa/demand', 'Demand View'),
                    'url' => ['course/view', 'id' => $model->course_id],
                    'template' => '<li class="course-name">{link}</li>',
                ],
                [
                    'label' => Yii::t('rcoa/demand', 'Demand Task Annexes').'：'.$model->name,
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container demand-task-annex-view has-title">
    <iframe class="col-lg-12 col-md-12 col-sm-12 col-xs-12" src="http://eezxyl.gzedu.com/o/?i=6824&furl=http://eefile.gzedu.com<?= $model->path?>" style="height: 550px;padding: 0px;">
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
