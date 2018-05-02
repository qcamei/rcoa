<?php

use wskeee\framework\models\ItemType;
use wskeee\rbac\components\ResourceHelper;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model ItemType */

$this->title = Yii::t('app', 'Details').'：'.$model->name;
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="main item-type-view">
    
    <div class="frame">
        <?= Yii::t('app', 'Detail') . '：' . $model->name?>
    </div>
    
    <p>
        <?= ResourceHelper::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= ResourceHelper::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]); ?>
    </p>
    
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            'id',
            'name',
        ],
    ]) ?>

</div>