<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemAttributesTemplate */

$this->title = Yii::t('rcoa/worksystem', 'Worksystem Attributes Templates');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Attributes Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-attributes-template-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/worksystem', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'worksystem_task_type_id',
                'value' => !empty($model->worksystem_task_type_id) ? $model->worksystemTaskType->name : null,
            ],
            [
                'attribute' => 'worksystem_attributes_id',
                'value' => !empty($model->worksystem_attributes_id) ? $model->worksystemAttributes->name : null,
            ],
            'index',
            [
                'attribute' => 'is_delete',
                'value' => $model->is_delete == 0 ? '否' : '是',
            ],
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>

</div>
