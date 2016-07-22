<?php

use common\models\teamwork\CourseSummary;
use frontend\modules\teamwork\TwAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model CourseSummary */

$this->title = Yii::t('rcoa/teamwork', 'Update Course Summary');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Summaries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->course_id, 'url' => ['view', 'id' => $model->course_id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/teamwork', 'Update');
?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb','style'=> 'width:300px;'],
            'homeLink' => [
                'label' => Yii::t('rcoa/teamwork', 'Courses'),
                'url' => ['index'],
                'template' => '<li class="course-name" style="width:30px;">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa/teamwork', 'Course View').'：'.$model->course->course->name,
                    'url' => ['course/view', 'id' => $model->course_id],
                    'template' => '<li class="course-name" style="max-width:158px;min-width:58px">{link}</li>',
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Update Course Summary'),
                    'template' => '<li class="course-name active" style="width:112px;">{link}</li>',
                ],
            ],
        ]);?>
    </div>
</div>

<div class="container course-summary-update has-title">

    <?= $this->render('_form', [
        'model' => $model,
        'weekly' => $weekly,
    ]) ?>

</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['course/view', 'id' => $model->course_id], ['class' => 'btn btn-default']) ?>
        <?= Html::a(
                $model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'),
                'javascript:;', 
                ['id'=>'submit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php
$js = 
<<<JS
    $('#submit').click(function()
    {
        $('#course-summary-form').submit();
    });
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>