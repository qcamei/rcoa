<?php

use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TwAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model ItemManage */

$this->title = Yii::t('rcoa/teamwork', 'Update Item Manage');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Item Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>

<div class="title">
    <div class="container">
         <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/teamwork', 'Items'),
                'url' => ['list'],
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa', 'Detail'),
                    'url' => ['view', 'id' => $model->id],
                ],
                [
                    'label' => Yii::t('rcoa', 'Update'),
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container item-manage-update has-title ">

    <?= $this->render('_form', [
        'model' => $model,
        'itemType' => $itemType,
        'items' => $items,
        'itemChilds' => $itemChilds,
    ]) ?>

</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
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
        $('#item-manage-form').submit();
    });
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>
