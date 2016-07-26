<?php

use common\models\teamwork\CourseAnnex;
use frontend\modules\teamwork\TwAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model CourseAnnex */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Annexes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/teamwork', 'Courses'),
                'url' => ['course/index'],
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa/teamwork', 'Course View'),
                    'url' => ['course/view', 'id' => $model->course_id],
                    'template' => '<li class="course-name">{link}</li>',
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Course Annexes').'：'.$model->name,
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container course-annex-view has-title item-manage">
    <iframe class="col-lg-12 col-md-12 col-sm-12 col-xs-12" src="http://officeweb365.com/o/?i=6824&furl=http://eefile.gzedu.com<?= $model->path?>" style="height: 550px;padding: 0px;">
    </iframe>
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['course/view', 'id' => $model->course_id], ['class' => 'btn btn-default',/*'onclick'=>'history.go(-1)'*/]) ?>
    </div>
</div>

<?php
    TwAsset::register($this);
?>
