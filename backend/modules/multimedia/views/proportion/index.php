<?php

use common\models\multimedia\MultimediaTypeProportion;
use common\models\multimedia\searchs\MultimediaTypeProportionSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel MultimediaTypeProportionSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/multimedia', 'Multimedia Type Proportions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="multimedia-type-proportion-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/multimedia', 'Create Multimedia Type Proportion'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'content_type',
                'value' => function($model){
                    /* @var $model MultimediaTypeProportion*/
                    return $model->contentType->name;
                }
            ],
            'proportion',
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    /* @var $model MultimediaTypeProportion*/
                    return date('Y-m', $model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    /* @var $model MultimediaTypeProportion*/
                    return date('Y-m', $model->updated_at);
                }
            ],
           
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
