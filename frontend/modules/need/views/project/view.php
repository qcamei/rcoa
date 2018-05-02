<?php

use kartik\helpers\Html;
use wskeee\framework\models\Project;
use wskeee\rbac\components\ResourceHelper;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Project */

$this->title = $model->name;
if ($model->parent_id != null) {
    $this->params['breadcrumbs'][] = ['label' => $model->parent->name, 'url' => ['/need/college/view', 'id' => $model->parent_id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main project-view">

    <div class="frame">
        <?php   
            if ($model->parent_id != null) {
                echo Html::a($model->parent->name, ['/need/college/view', 'id' => $model->parent_id])
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
    
    <h4><b><?= Yii::t('app', 'Courses') ?></b></h4>
    <p>
        <?= ResourceHelper::a(Yii::t('app', '{Create}{Courses}',[
                'Create' => Yii::t('app', 'Create'), 
                'Courses' => Yii::t('app', 'Courses')
            ]), ['course/create', 'parent_id' => $model->id], ['class' => 'btn btn-success']);
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{summary}\n{pager}",
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['style' => 'width:50px'],
            ],
            [
                'class' => 'frontend\modules\demand\components\GridViewLinkCell',
                'attribute' => 'name',
                'url' => '/need/course/view'
            ],
            [
                'class' => 'common\components\RbacActionColumn',
                'options' => ['style' => ['width' => '70px']],
                'customController' => 'course',
                'buttonUrlParams' => [
                    'delete' => ['callback' => "/need/project/view?id=$model->id"]
                ]
            ],
        ],
    ]); ?>
</div>
