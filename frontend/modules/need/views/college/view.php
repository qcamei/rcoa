<?php

use wskeee\framework\models\College;
use wskeee\rbac\components\ResourceHelper;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model College */

$this->title = Yii::t('app', 'Detail').'：'.$model->name;
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="main college-view">
    
    <div class="frame">
        <?= Yii::t('app', 'Detail') . '：' . $model->name?>
    </div>
    
    <p>
        <?= ResourceHelper::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= ResourceHelper::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('Item ID', 'Are you sure you want to delete this item?'),
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
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>
    
    <h4><b><?= Yii::t('app', 'Item Child ID') ?></b></h4>
    
    <p>
         <?= ResourceHelper::a(Yii::t('app', '{Create} {Item Child ID}', ['Create' => Yii::t('rcoa/basedata', 'Create'), 'Item Child ID' => Yii::t('app', 'Item Child ID')]), 
                ['project/create', 'parent_id' => $model->id], ['class' => 'btn btn-success']); ?>
    </p>

    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{summary}\n{pager}",
        'tableOptions' => ['class' => 'table table-striped table-bordered','style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options'=>['style'=>'width:50px'],
            ],
            [
                'class' => 'frontend\modules\need\components\GridViewLinkCell',
                'attribute'=>'name',
                'url'=>'/need/project/view'
            ],
            [
                'class' => 'common\components\RbacActionColumn',
                'options' => ['style'=>['width' => '70px']],
                'customController' => 'project',
                'buttonUrlParams' => [
                    'delete' => ['callback'=> "/need/college/view?id=$model->id"]
                ]
            ],
        ],
    ]); ?>

</div>
