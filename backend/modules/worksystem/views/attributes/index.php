<?php

use common\models\worksystem\searchs\WorksystemAttributestSearch;
use common\models\worksystem\WorksystemAttributes;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel WorksystemAttributestSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/worksystem', 'Worksystem Attributes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-attributes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/worksystem', 'Create Worksystem Attributes'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'worksystem_task_type_id',
                'value' => function ($model){
                    /* @var $model WorksystemAttributes */
                    return !empty($model->worksystem_task_type_id) ? $model->worksystemTaskType->name : null;
                }
            ],
            'name',
            [
                'attribute' => 'type',
                'value' => function ($model){
                    /* @var $model WorksystemAttributes */
                    return WorksystemAttributes::$typeName[$model->type];
                }
            ],
            [
                'attribute' => 'input_type',
                'value' => function ($model){
                    /* @var $model WorksystemAttributes */
                    return WorksystemAttributes::$inputTypeName[$model->input_type];
                }
            ],
            // 'value_list:ntext',
            // 'index',
            // 'is_delete',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
