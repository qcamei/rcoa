<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\team\searchs\TeamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/team', 'Teams');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/team', 'Create Team Type'), ['type/create'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa/team', 'Create Team'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            [
                'attribute' => 'type',
                'value' => function($model){
                    return $model->teamType->name;
                }
            ],
            'des',
            'index',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
