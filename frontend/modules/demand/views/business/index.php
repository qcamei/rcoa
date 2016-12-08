<?php

use frontend\modules\demand\assets\BasedataAssets;
use wskeee\framework\models\searchs\ItemTypeSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ItemTypeSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/basedata', 'Item Types');
?>
<div class="container item-type-index">

    <p>
        <?= Html::a(
                Yii::t('rcoa/basedata', '{Create} {Item Type}',['Create'=>  Yii::t('rcoa/basedata', 'Create'),'Item Type'=>Yii::t('rcoa/basedata', 'Item Type')]), 
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
                'url'=>'/demand/business/view'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options'=>['style'=>'width:70px']
            ],
        ],
    ]); ?>
</div>
