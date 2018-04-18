<?php

use common\models\workitem\Workitem;
use yii\data\ArrayDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Workitem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/workitem', 'Workitems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workitem-view">

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php $model->cover = WEB_ROOT . $model->cover; ?>
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            'id',
            'name',
            'cover:image',
            'index',
            'unit',
            'des',
            'created_at:datetime',
            'updated_at:datetime',
            'content:raw',
        ],
    ]) ?>
    
    <p>
        <?php
            echo Html::a(Yii::t('rcoa/workitem', 'Create Cabinet'), ['cabinet/create', 'workitem_id' => $model->id], ['class' => 'btn btn-success']);
        ?>
    </p>
    <?=GridView::widget([
        'dataProvider' => new ArrayDataProvider(['models'=>$model->cabinets]),
        'tableOptions' => ['class' => 'table table-striped table-bordered','style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options'=>['style'=>'width:50px'],
            ],
            'attribute'=>'path',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update} {delete}',
                'options'=>['style'=>'width:70px'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['cabinet/view', 'id' => $model->id], $options);
                    },
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['cabinet/update', 'id' => $model->id], $options);
                    },
                    'delete' => function ($url, $cmodel, $key)use($model) {
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-pjax' => '0',
                            'data-method' => 'post'
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['cabinet/delete', 'id' => $cmodel->id,'callback'=>"/demand/college/view?id=$model->id"], $options);
                    }
                ]
            ],
        ],
    ]); ?>
</div>
