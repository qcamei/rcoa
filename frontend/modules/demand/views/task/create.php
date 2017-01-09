<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;


/* @var $this View */
/* @var $model DemandTask */

$this->title = Yii::t('rcoa/demand', 'Create Demand Task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/demand', 'Demand Tasks'),
                'url' => ['index'],
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa', 'Create'),
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container demand-task-create has-title">

    <?= $this->render('_form', [
        'model' => $model,
        'itemTypes' => $itemTypes,
        'items' => $items,
        'itemChilds' => $itemChilds,
        'courses' => $courses,
        'teachers' => $teachers,
        'team' => $team,
        'mark' => 0,
    ]) ?>

</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
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
        
        $.post("/demand/task/check-unique", $('#demand-task-form').serialize(), function(data){
            if(data['types'] == 1){
                $(".field-demandtask-course_id").addClass("has-error").removeClass("has-success");
                $(".field-demandtask-course_id .help-block").text(data['message']);
            }else{
               $('#demand-task-form').submit();
            }
        })
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
?>