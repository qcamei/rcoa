<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\scene\searchs\SceneSiteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '{Scene}{List}{Administration}',[
    'Scene' => Yii::t('app', 'Scene'),
    'List' => Yii::t('app', 'List'),
    'Administration' => Yii::t('app', 'Administration'),
]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-site-index">

    <h1><?php //Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', '{Create}{Scene}',[
            'Create' => Yii::t('app', 'Create'),
            'Scene' => Yii::t('app', 'Scene'),
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'op_type',
            'area',
            'name',
            'content_type',
            'manager_id',
            'is_publish',
            'sort_order',
            //'country',
            // 'province',
            // 'city',
            // 'district',
            // 'twon',
            // 'address',
            // 'price',
            // 'contact',
            // 'img_path',
            // 'des',
            // 'location',
            // 'content:ntext',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
