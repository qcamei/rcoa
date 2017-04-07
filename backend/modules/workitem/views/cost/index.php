<?php

use common\models\workitem\searchs\WorkitemCostSearch;
use common\models\workitem\WorkitemCost;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel WorkitemCostSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/workitem', 'Workitem Costs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workitem-cost-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/workitem', 'Create Workitem Cost'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'workitem_id',
                'format' => 'raw',
                'value'=> function($model){
                   /* @var $model WorkitemCost */
                   return !empty($model->workitem_id) ? $model->workitem->name : null;
                },
               
            ],
            [
                'attribute' => 'cost_new',
                'format' => 'raw',
                'value'=> function($model){
                   /* @var $model WorkitemCost */
                   return !empty($model->cost_new) ? '￥'.$model->cost_new : null;
                },
               
            ],
            [
                'attribute' => 'cost_remould',
                'format' => 'raw',
                'value'=> function($model){
                   /* @var $model WorkitemCost */
                   return !empty($model->cost_remould) ? '￥'.$model->cost_remould : null;
                },
               
            ],
            'target_month',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
