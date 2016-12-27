<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model DemandTask */

$this->title = Yii::t('rcoa/demand', 'Update Demand Task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/demand', 'Update');
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
                    'label' => Yii::t('rcoa', 'Update'),
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container demand-task-update has-title">

    <?= $this->render('_form', [
        'model' => $model,
        'itemTypes' => $itemTypes,
        'items' => $items,
        'itemChilds' => $itemChilds,
        'courses' => $courses,
        'teachers' => $teachers,
        'team' => $team,
        'annex' => $annex,
        'mark' => $mark,
    ]) ?>

</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        <?php
            if($mark == true)
                echo Html::a('提交审核', ['submit-check', 'id' => $model->id], ['class' => 'btn btn-primary']);
            else    
                echo Html::a(
                    $model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'),
                    'javascript:;', 
                    ['id'=>'submit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) 
        ?>
    </div>
</div>

<?php
$js = 
<<<JS
   
    /** 任务更新操作 提交表单 */
    $('#submit').click(function()
    {
        $('#demand-task-form').submit();
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
?>