<?php

use common\models\multimedia\MultimediaContentType;
use common\models\multimedia\MultimediaTypeProportion;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model MultimediaContentType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Content Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="multimedia-content-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'des',
            'index',
        ],
    ]) ?>

<h5>类型比例</h5>

<p>
    <?= Html::a(Yii::t('rcoa/multimedia', 'Create Multimedia Type Proportion'), ['proportion/create', 'content_type' => $model->id], [
        'class' => 'btn btn-success'
    ])?>
</p>

<?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->proportions,
        ]),
        'columns' =>[
            [
                'label'=>  Yii::t('rcoa/multimedia', 'Name Type'),
                'value'=>function($model){
                    /* @var $model MultimediaTypeProportion */
                    return $model->contentType->name;
                }   
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Proportion'),
                'value' => function($model){
                    /* @var $model MultimediaTypeProportion */
                    return ($model->proportion / 10) * 10;
                }
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Target Month'),
                'value' => function($model){
                    /* @var $model MultimediaTypeProportion */
                    return $model->target_month;
                }
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Created At'),
                'value' => function($model){
                    /* @var $model MultimediaTypeProportion */
                    return date('Y-m-d H:i', $model->created_at);
                }
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Updated At'),
                'value' => function($model){
                    /* @var $model MultimediaTypeProportion */
                    return date('Y-m-d H:i', $model->updated_at);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
                            ['proportion/update', 'id' => $model->id], $options);
                    },
                    'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-pjax' => '0',
                            'data' => [
                                        'method' => 'post'
                                        ]
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                            ['proportion/delete', 'id' => $model->id], $options);
                    },       
                ],
                'template' => '{update}{delete}',
            ],
        ]
    ]) ?>

</div>