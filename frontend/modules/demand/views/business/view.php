<?php

use wskeee\framework\models\ItemType;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model ItemType */

$this->title = Yii::t('rcoa/basedata', 'Details').'：'.$model->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container item-type-view">
    <p>
        <?= Html::a(Yii::t('rcoa/basedata', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa/basedata', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/basedata', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
        ],
    ]) ?>

</div>