<?php

use common\models\worksystem\searchs\WorksystemAttributesTemplateSearch;
use common\models\worksystem\WorksystemAttributesTemplate;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel WorksystemAttributesTemplateSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/worksystem', 'Worksystem Attributes Templates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-attributes-template-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/worksystem', 'Create Worksystem Attributes Template'), ['create'], ['class' => 'btn btn-success']) ?>
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
                    /* @var $model WorksystemAttributesTemplate */
                    return !empty($model->worksystem_task_type_id) ? $model->worksystemTaskType->name : null;
                },
            ],
            [
                'attribute' => 'worksystem_attributes_id',
                'value' => function ($model){
                    /* @var $model WorksystemAttributesTemplate */
                    return !empty($model->worksystem_attributes_id) ? $model->worksystemAttributes->name : null;
                },
            ],
            'index',
            [
                'attribute' => 'is_delete',
                'value' => function ($model){
                    /* @var $model WorksystemAttributesTemplate */
                    return $model->is_delete == 0 ? '否' : '是';
                },
            ],
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
