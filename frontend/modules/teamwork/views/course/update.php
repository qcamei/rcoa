<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model CourseManage */

$this->title = Yii::t('rcoa/teamwork', 'Update Item Manage');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/teamwork', 'Update');
?>

<div class="title">
    <div class="container">
        <?= $this->title.'：'.$model->course->name ?>
    </div>
</div>

<div class="container course-manage-update has-title">

    <?= $this->render('_form', [
        'model' => $model,
        'courses' => $courses,
        'teachers' => $teachers,
        'producerList' => $producerList,
        'producer' => $producer,
    ]) ?>

</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['list','project_id' => $model->project_id], ['class' => 'btn btn-default']) ?>
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
        $('#course-manage-form').submit();
    });
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>