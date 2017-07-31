<?php

use common\models\worksystem\searchs\WorksystemContentSearch;
use common\models\worksystem\WorksystemContent;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel WorksystemContentSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/worksystem', 'Worksystem Contents');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-content-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/worksystem', 'Create Worksystem Content'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'worksystem_task_type_id',
                'value' => function($model){
                    /* @var $model WorksystemContent */
                    return !empty($model->worksystem_task_type_id) ? $model->worksystemTaskType->name : null;
                },
            ],
            'type_name',
            //'icon',
            [
                'attribute' => 'price_new',
                'value' => function($model){
                    /* @var $model WorksystemContent */
                    return '￥'. number_format($model->price_new, 2, '.', ',');
                },
            ],
            [
                'attribute' => 'price_remould',
                'value' => function($model){
                    /* @var $model WorksystemContent */
                    return '￥'. number_format($model->price_remould, 2, '.', ',');
                },
            ],
            'unit',
            // 'des:ntext',
            // 'index',
            // 'is_delete',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
