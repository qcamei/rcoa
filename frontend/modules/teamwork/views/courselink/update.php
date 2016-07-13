<?php

use common\models\teamwork\CourseLink;
use frontend\modules\teamwork\TwAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model CourseLink */

$this->title = Yii::t('rcoa/teamwork', 'Update Course Phase Link');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $phaseModel->id, 'url' => ['view', 'id' => $phaseModel->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/teamwork', 'Update');
?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/teamwork', 'Courses'),
                'url' => ['course/index'],
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa', 'Detail'),
                    'url' => ['course/view', 'id' => $phaseModel->course_id],
                ],
                [
                    'label' => Yii::t('rcoa', 'Deploy'),
                    'url' => ['index', 'course_id' => $phaseModel->course_id],
                ],
                [
                    'label' => Yii::t('rcoa', '阶段编辑'),
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container course-link-update has-title">

    <?= $this->render('_form', [
        'phaseModel' => $phaseModel,
        'phase' => $phase,
        'link' => $link,
    ]) ?>

</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['index','course_id' => $phaseModel->course_id], ['class' => 'btn btn-default']) ?>
        
        <?= Html::a('更新', 'javascript:;', ['id' => 'submit', 'class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php
$js = 
<<<JS
    $('#submit').click(function()
    {
        $('#course-form').submit();
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>