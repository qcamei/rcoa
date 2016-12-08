<?php

use frontend\modules\demand\assets\BasedataAssets;
use wskeee\framework\models\searchs\ItemSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ItemSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('demand', 'Colleges');
?>
<div class="container college-index">

    <p>
        <?= Html::a(
                Yii::t('rcoa/basedata', '{Create} {College}',['Create'=>  Yii::t('rcoa/basedata', 'Create'),'College'=>Yii::t('rcoa/basedata', 'College')]), 
                ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options'=>['style'=>'width:50px'],
            ],
           [
                'class' => 'frontend\modules\demand\components\GridViewLinkCell',
                'attribute'=>'name',
                'url'=>'/demand/college/view'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options'=>['style'=>'width:70px']
            ],
        ],
    ]); ?>
</div>
