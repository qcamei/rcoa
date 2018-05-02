<?php

use wskeee\framework\models\Course;
use wskeee\rbac\components\ResourceHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Course */

$this->title = Yii::t('app', '{Detail} {Courses}',['Detail'=>  Yii::t('app', 'Detail'),'Courses'=>  Yii::t('app', 'Courses')]);
if($model->parent_id != null)
{
    $this->params['breadcrumbs'][] = ['label' => $model->parent->parent->name, 'url' => ['/need/college/view','id'=>$model->parent->parent_id]];
    $this->params['breadcrumbs'][] = ['label' => $model->parent->name, 'url' => ['/need/project/view','id'=>$model->parent_id]];
}
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="main course-view">

    <div class="frame">
        <?php   
            if ($model->parent_id != null) {
                echo Html::a($model->parent->parent->name, ['/need/college/view', 'id' => $model->parent->parent_id])
                        . ' / ' . Html::a($model->parent->name, ['/need/project/view', 'id' => $model->parent_id])
                            . ' / ' . $model->name;
            } else {
                echo Yii::t('app', 'Detail') . '：' . $model->name;
            }
        ?>
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
            'des',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
</div>
