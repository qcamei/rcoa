<?php

use wskeee\framework\models\College;
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
        <?php
        if ($rbac['update']) {
            echo Html::a(Yii::t('rcoa/basedata', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
        }
        if ($rbac['delete']) {
            echo Html::a(Yii::t('rcoa/basedata', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('demand', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
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
        <?php
        if ($rbac['create']) {
            echo Html::a(Yii::t('rcoa/basedata', '{Create} {Project}', ['Create' => Yii::t('rcoa/basedata', 'Create'), 'Project' => Yii::t('rcoa/basedata', 'Project')]), 
                ['project/create', 'parent_id' => $model->id], ['class' => 'btn btn-success']);
        }
        ?>
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
                'class' => ActionColumn::className(),
                'template' => '{view} {update} {delete}',
                'options'=>['style'=>'width:70px'],
                'visibleButtons' => [
                    'create' => $rbac['create'],
                    'update' => $rbac['update'],
                    'delete' => $rbac['delete'],
                ],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['project/view', 'id' => $key], $options);
                    },
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['project/update', 'id' => $key], $options);
                    },
                    'delete' => function ($url, $model, $key)use($model) {
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-pjax' => '0',
                            'data-method' => 'post'
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['project/delete', 'id' => $key,'callback'=>"/demand/college/view?id=$model->id"], $options);
                    }
                ]
            ],
        ],
    ]); ?>

</div>
