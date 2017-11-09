<?php

use wskeee\framework\models\Project;
use wskeee\rbac\components\ResourceHelper;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Project */

$this->title = $model->name;
if ($model->parent_id != null)
    $this->params['breadcrumbs'][] = ['label' => $model->parent->name, 'url' => ['/demand/college/view', 'id' => $model->parent_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container project-view">

    <p>
        <?= ResourceHelper::a(Yii::t('rcoa/basedata', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?=
        ResourceHelper::a(Yii::t('rcoa/basedata', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/basedata', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]);
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'des',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ])
    ?>
    <h4><b><?= Yii::t('rcoa/basedata', 'Course') ?></b></h4>
    <p>
    <?= ResourceHelper::a(Yii::t('rcoa/basedata', '{Create} {Course}',[
            'Create' => Yii::t('rcoa/basedata', 'Create'), 
            'Course' => Yii::t('rcoa/basedata', 'Course')
        ]), ['course/create', 'parent_id' => $model->id], ['class' => 'btn btn-success']);
    ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['style' => 'width:50px'],
            ],
            [
                'class' => 'frontend\modules\demand\components\GridViewLinkCell',
                'attribute' => 'name',
                'url' => '/demand/course/view'
            ],
            [
                'class' => 'common\components\RbacActionColumn',
                'options' => ['style' => ['width' => '70px']],
                'customController' => 'course',
                'buttonUrlParams' => [
                    'delete' => ['callback' => "/demand/project/view?id=$model->id"]
                ]
            ],
        ],
    ]);
    ?>
</div>
