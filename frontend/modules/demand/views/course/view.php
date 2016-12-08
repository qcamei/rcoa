<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\Course */

$this->title = Yii::t('rcoa/basedata', '{Detail} {Course}',['Detail'=>  Yii::t('rcoa/basedata', 'Detail'),'Course'=>  Yii::t('rcoa/basedata', 'Course')]);
if($model->parent_id != null)
{
    $this->params['breadcrumbs'][] = ['label' => $model->parent->parent->name, 'url' => ['/demand/college/view','id'=>$model->parent->parent_id]];
    $this->params['breadcrumbs'][] = ['label' => $model->parent->name, 'url' => ['/demand/project/view','id'=>$model->parent_id]];
}
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="container course-view">

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
            'des',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
</div>
