<?php

use common\models\resource\Resource;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Resource */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/resource', 'Resources'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resource-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/resource', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'type',
            'image',
            'des',
        ],
    ]) ?>
    
    <h5>添加资源展示信息</h5>
    
    <p>
        <?= Html::a(Yii::t('rcoa/resource', 'Add Resource'), ['path/create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->resourcePaths,
        ]),
        'columns' => [
            
            'r_id',
            'path',
            [
                'attribute' => 'type',
                'value' => function ($model){
                    return $model->type == 0 ? '图片' : '视频';
                },
            ],
            [
                'attribute' => 'des',
                'format' => 'raw',
                'value' => function ($model){
                    return $model->des;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
                            ['/resource/path/update', 'id' => $model->id], $options);
                    },
                    'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                            'data' => [
                                        'method' => 'post'
                                        ]
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                            ['/resource/path/delete', 'id' => $model->id], $options);
                    },       
                ],
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>
</div>
