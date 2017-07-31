<?php

use common\models\team\TeamCategory;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model TeamCategory */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', '{Team}{Categories}',['Team'=>  Yii::t('rcoa/team', 'Team'),'Categories'=>  Yii::t('rcoa/team', 'Categories')]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-category-view">

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
            'is_delete',
        ],
    ]) ?>
    
    <p>
        <?= Html::a(
                Yii::t('rcoa', '{Add} {Team}',['Add'=>  Yii::t('rcoa', 'Add'),'Team'=>Yii::t('rcoa/team', 'Team')]), 
                ['team-category-map/create','category_id'=>$model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?=GridView::widget([
        'dataProvider' => $children,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options'=>['style'=>'width:50px'],
            ],
            'team.name',
            [
                'attribute'=>'index',
                'options'=>['style'=>'width:50px'],
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{update} {delete}',
                'options'=>['style'=>'width:70px'],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['team-category-map/view', 'id' => $key], $options);
                    },
                    'update' => function ($url, $itemmodel, $key)use($model) {
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', [
                            'team-category-map/update', 
                            'id' => $key,
                            'category_id' => $itemmodel->category_id,
                            'team_id' => $itemmodel->team_id,
                            'callback'=>"/teammanage_admin/team-category/view?id=$model->id"], $options);
                    },
                    'delete' => function ($url, $itemmodel, $key)use($model) {
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-pjax' => '0',
                            'data-method' => 'post'
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',[
                                'team-category-map/delete', 
                                'category_id' => $itemmodel->category_id,
                                'team_id' => $itemmodel->team_id,
                                'callback'=>"/teammanage_admin/team-category/view?id=$model->id"
                            ], $options);
                    }
                ]
            ],
        ],
    ]); ?>

</div>
