<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchs\SystemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Systems';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-index">

    <h1>创建</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'module_image',
            'module_link',
            'des',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>