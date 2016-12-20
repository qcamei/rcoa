<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\team\searchs\TeamCategory */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/team', '{Team}{Categories}',['Team'=>  Yii::t('rcoa/team', 'Team'),'Categories'=>  Yii::t('rcoa/team', 'Categories')]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-category-index">

    <p>
        <?= Html::a(Yii::t('rcoa/team', '{Create} {Team}{Category}',[
            'Create'=>Yii::t('rcoa', 'Create'),
            'Team'=>Yii::t('rcoa/team', 'Team'),
            'Category'=>Yii::t('rcoa/team', 'Category'),
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'des',
            'is_delete',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
