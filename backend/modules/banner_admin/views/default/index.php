<?php

use common\models\Banner;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/banner', 'Banners');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'name',
            'path',
            'link',
            'des',
            'index',
            [
                'attribute' => 'isdisplay',
                'value' => function($model){
                    return $model->isdisplay == Banner::DISPLAY ? "是" : "否";
                }
            ],
           
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
