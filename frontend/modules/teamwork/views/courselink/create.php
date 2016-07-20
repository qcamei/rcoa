<?php

use common\models\teamwork\CourseLink;
use frontend\modules\teamwork\TwAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;


/* @var $this View */
/* @var $model CourseLink */

$this->title = Yii::t('rcoa/teamwork', 'Create Course Phase Link');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                    'label' => Yii::t('rcoa/teamwork', 'Course View'),
                    'url' => ['course/view', 'id' => $phaseModel->course_id],
                ],
                [
                    'label' => Yii::t('rcoa', 'Deploy'),
                    'url' => ['index', 'course_id' => $phaseModel->course_id],
                ],
                [
                    'label' => Yii::t('rcoa', '阶段添加'),
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container course-link-create has-title">

    <?= $this->render('_form', [
        'phaseModel' => $phaseModel,
        'phase' => $phase,
        'link' => $link,
    ]) ?>

</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['index','course_id' => $course_id], ['class' => 'btn btn-default']) ?>
        
        <?= Html::a('保存', 'javascript:;', ['id' => 'submit', 'class' => 'btn btn-primary']) ?>
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
