<?php

use common\models\demand\DemandWeightTemplate;
use common\models\demand\searchs\DemandWeightTemplateSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DemandWeightTemplateSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/demand', 'Demand Weight Templates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-weight-template-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/demand', 'Create Demand Weight Template'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'workitem_type_id',
                'value' => function($model){
                    /* @var $model DemandWeightTemplate*/
                    return !empty($model->workitem_type_id) ? $model->workitemType->name : null;
                }
            ],
            'weight',
            'sl_weight',
            'zl_weight',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
