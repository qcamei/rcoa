<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchs\PositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/position', 'Positions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/position', 'Create Position'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'des',
            'level',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
