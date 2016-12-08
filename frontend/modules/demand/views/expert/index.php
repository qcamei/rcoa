<?php

use common\models\expert\searchs\ExpertSearch;
use frontend\modules\demand\assets\BasedataAssets;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ExpertSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/basedata', 'Expert');
?>
<div class="container expert-index">

    <p>
        <?= Html::a(
                Yii::t('rcoa/basedata', '{Create} {Expert}',['Create'=>  Yii::t('rcoa/basedata', 'Create'),'Expert'=>  Yii::t('rcoa/basedata', 'Expert')]), 
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
                'attribute'=>'personal_image',
                'format'=>['Image',['style'=>'width:40px;height:40px;']],
            ],
            [
                'attribute'=>'username',
                'value'=>'user.username',
                'options'=>['style'=>'width:73px'],
            ],
            [
                'attribute'=>'nickname',
                'options'=>['style'=>'width:73px'],
            ],
            [
                'attribute'=>'birth',
                'options'=>['style'=>'width:50px'],
                'filter'=>false,
            ],
            'employer',
            'job_title',
            [
                'class' => 'yii\grid\ActionColumn',
                'options'=>['style'=>'width:70px']
            ],
        ],
    ]); ?>
</div>