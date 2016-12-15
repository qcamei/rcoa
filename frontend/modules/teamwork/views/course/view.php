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
                'url' => ['index', 'status' => CourseManage::STATUS_NORMAL],
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa/teamwork', 'Course View').'：'.$model->demandTask->course->name,
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container course-manage-view has-title item-manage">

   <?= $this->render('_form_detai', [
        'model' => $model,
        'twTool' => $twTool,
        'producers' => $producers,
    ]) ?>
    
    <span><?= Yii::t('rcoa/teamwork', 'Course Accessories').'：'; ?></span>
    <?php
        foreach ($annex as $value) {
            echo Html::a($value->name, ['annex/view', 'id' => $value->id], ['style' => 'margin-right:10px;']);
        }
    ?>
    
    <?= $this->render('/summary/index', [
        'model' => $model,
        'twTool' => $twTool,
        'weeklyMonth' => $weeklyMonth,
        'weeklyInfoResult' => $weeklyInfoResult
    ]); ?>
    
</div>

<?= $this->render('_form_view',[
    'model' => $model,
    'twTool' => $twTool,
]) ?>

<div class="item-manage">
    
    <?= $this->render('_form_model')?>
    
</div>

<?php
$js = 
<<<JS
    /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。 */
    $('.myModal').on('hidden.bs.modal', function(){
        window.location.reload();
    }); 
    /** 移交操作 弹出模态框 */
    $('#change').click(function()
    {
        var urlf = $(this).attr("href");
        $('.myModal').modal({remote:urlf});
        return false;
    });        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>
