<?php

use common\models\demand\DemandWorkitemTemplate;
use common\models\demand\searchs\DemandWorkitemTemplateSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DemandWorkitemTemplateSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/demand', 'Demand Workitem Templates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-workitem-template-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/demand', 'Create Demand Workitem Template'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'demand_workitem_template_type_id',
                'value' => function($model){
                    /* @var $model DemandWorkitemTemplate*/
                    return !empty($model->demand_workitem_template_type_id) ? $model->demandWorkitemTemplateType->name : null;
                }
            ],
            [
                'attribute' => 'workitem_type_id',
                'value' => function($model){
                    /* @var $model DemandWorkitemTemplate*/
                    return !empty($model->workitem_type_id) ? $model->workitemType->name : null;
                }
            ],
            [
                'attribute' => 'workitem_id',
                'value' => function($model){
                    /* @var $model DemandWorkitemTemplate*/
                    return !empty($model->workitem_id) ? $model->workitem->name : null;
                }
            ],
            [
                'attribute' => 'is_new',
                'value' => function($model){
                    /* @var $model DemandWorkitemTemplate*/
                    return $model->is_new == true ? '是' : '否';
                }
            ],
           
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
