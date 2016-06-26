<?php

use wskeee\framework\models\searchs\PhaseSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel PhaseSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/framework', 'Phases');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phase-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/framework', 'Create Phase'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'weights',
            'progress',
            [
                'attribute' => 'create_by',
                'value' => function($model){
                    return $model->createBy->nickname;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
