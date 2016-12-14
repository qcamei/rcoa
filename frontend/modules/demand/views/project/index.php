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

$this->title = Yii::t('demand', 'Projects');
?>
<div class="container project-index">

    <p>
        <?= Html::a(
                Yii::t('rcoa/basedata', '{Create} {Project}',['Create'=>  Yii::t('rcoa/basedata', 'Create'),'Project'=>  Yii::t('rcoa/basedata', 'Project')]), 
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
                'attribute'=>'college',
                'url'=>'/demand/college/view',
                'key'=>'college_id',
            ],
            [
                'class' => 'frontend\modules\demand\components\GridViewLinkCell',
                'attribute'=>'name',
                'url'=>'/demand/project/view'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options'=>['style'=>'width:70px']
            ],
        ],
    ]); ?>
</div>