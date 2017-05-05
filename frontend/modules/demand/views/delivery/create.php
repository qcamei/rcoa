<?php

use common\models\demand\DemandDelivery;
use Detection\MobileDetect;
use frontend\modules\demand\assets\DemandAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;


/* @var $this View */
/* @var $model DemandDelivery */
/* @var $detect MobileDetect */

$this->title = Yii::t('rcoa/demand', 'Create Demand Delivery');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Deliveries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb breadcrumb-title'],
            'homeLink' => [
                'label' => Yii::t('rcoa/demand', 'Demand Tasks'),
                'url' => ['task/index'],
            ],
            'links' => [
                [
                    'label' => $model->demandTask->course->name,
                    'url' => ['task/view', 'id' => $model->demand_task_id]
                ],
                [
                    'label' => Yii::t('rcoa/demand', 'Demand Deliveries'),
                ],
            ]
        ]);?>
    </div>
</div>

<?php if(!$detect->isMobile()): ?>
<div class="container demand-delivery-create has-title">
<?php else: ?>
<div class="demand-delivery-create has-title">
<?php endif; ?>

    <?= $this->render(!$detect->isMobile() ? '_form' : 'wap_form', [
        'model' => $model,
        'workitemType' => $workitemType,
        'workitem' => $workitem,
    ]) ?>

</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['task/view', 'id' => $model->demand_task_id], ['class' => 'btn btn-default']) ?>
        <?= Html::a(Yii::t('rcoa', '提交'), 'javascript:;',  ['id'=>'submit', 'class' => 'btn btn-info']) ?>
    </div>
</div>

<?php
$js = 
<<<JS
    $('#submit').click(function(){
        $('#demand-delivery-form').submit();
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
?>