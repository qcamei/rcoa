<?php

use wskeee\framework\models\Project;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Project */

$this->title = $model->name;
if($model->parent_id != null)
    $this->params['breadcrumbs'][] = ['label' => $model->parent->name, 'url' => ['/demand/college/view','id'=>$model->parent_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container project-view">

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
    <p>
        <?= Html::a(Yii::t('rcoa/basedata', '{Create} {Course}',['Create'=>  Yii::t('rcoa/basedata', 'Create'),'Course'=>  Yii::t('rcoa/basedata', 'Course')]), 
                ['course/create','parent_id'=>$model->id], 
                ['class' => 'btn btn-success'/*, 'data' => ['method' => 'post']*/]) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options'=>['style'=>'width:50px'],
            ],
            [
                'class' => 'frontend\modules\demand\components\GridViewLinkCell',
                'attribute'=>'name',
                'url'=>'/demand/course/view'
            ],
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
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['course/view', 'id' => $key], $options);
                    },
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['course/update', 'id' => $key], $options);
                    },
                    'delete' => function ($url, $model, $key) use($model){
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-pjax' => '0',
                            'data-method' => 'post'
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['course/delete', 'id' => $key,'callback'=>"/demand/project/view?id=$model->id"], $options);
                    }
                ]
            ]]
    ]);?>
</div>
