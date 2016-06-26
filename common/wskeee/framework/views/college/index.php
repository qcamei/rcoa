<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel wskeee\framework\models\searchs\CollegeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/framework', 'Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="college-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/framework', 'Create Item'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'des',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
