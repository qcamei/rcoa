<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use kartik\widgets\Select2;
use wskeee\rbac\RbacName;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;


/* @var $this View */
/* @var $model CourseManage */

$this->title = Yii::t('rcoa/teamwork', 'Course View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/teamwork', 'Courses'),
                'url' => ['index'],
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa/teamwork', 'Course View').'：'.$model->course->name,
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container course-manage-view has-title item-manage">

   <?= $this->render('_form_detai', [
        'model' => $model,
        'producer' => $producer,
    ]) ?>
    
    <span>课程附件：</span>
    <?php
        foreach ($annex as $value) {
            echo Html::a($value->name, ['annex/view', 'id' => $value->id], ['style' => 'margin-right:10px;']);
        }
    ?>
    
    <?= $this->render('/summary/index', [
        'model' => $model,
        'twTool' => $twTool,
        'weeklyMonth' => $weeklyMonth,
    ]); ?>
    
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), '#', ['class' => 'btn btn-default','onclick'=>'history.go(-1)']) ?>
        <?php
            /**
             * 编辑 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $twTool->getIsLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 配置 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $twTool->getIsLeader() && $model->create_by == Yii::$app->user->id)    
                echo Html::a('配置', ['/teamwork/courselink/index', 'course_id' => $model->id], ['class' => 'btn btn-primary']).' ';
           
            echo Html::a('进度', ['/teamwork/courselink/progress', 'course_id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 完成 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $twTool->getIsLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('完成', ['carry-out', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
            
            /**
             * 恢复 按钮显示必须满足以下条件：
             * 1、必须是状态为【已经完成】
             * 2、必须是【项目管理员】
            */
            if($model->getIsCarryOut() && Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER))
                echo Html::a('恢复', ['normal', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
        ?>
    </div>
</div>

<?php
$js = 
<<<JS
    
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>
