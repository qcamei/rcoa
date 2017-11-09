<?php

use wskeee\framework\models\College;
use wskeee\rbac\components\ResourceHelper;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model College */

$this->title = Yii::t('rcoa/basedata', 'Details').'：'.$model->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container college-view">

    <p>
        <?= ResourceHelper::a(Yii::t('rcoa/basedata', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= ResourceHelper::a(Yii::t('rcoa/basedata', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/basedata', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'des',
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>
    <h4><b><?= Yii::t('rcoa/basedata', 'Project') ?></b></h4>
    <p>
         <?= ResourceHelper::a(Yii::t('rcoa/basedata', '{Create} {Project}', ['Create' => Yii::t('rcoa/basedata', 'Create'), 'Project' => Yii::t('rcoa/basedata', 'Project')]), 
                ['project/create', 'parent_id' => $model->id], ['class' => 'btn btn-success']); ?>
    </p>

    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-bordered','style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options'=>['style'=>'width:50px'],
            ],
            [
                'class' => 'frontend\modules\demand\components\GridViewLinkCell',
                'attribute'=>'name',
                'url'=>'/demand/project/view'
            ],
            [
                'class' => 'common\components\RbacActionColumn',
                'options' => ['style'=>['width' => '70px']],
                'customController' => 'project',
                'buttonUrlParams' => [
                    'delete' => ['callback'=> "/demand/college/view?id=$model->id"]
                ]
            ],
        ],
    ]); ?>

</div>
