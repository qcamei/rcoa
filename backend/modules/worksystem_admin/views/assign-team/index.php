<?php

use common\models\worksystem\searchs\WorksystemAssignTeamSearch;
use common\models\worksystem\WorksystemAssignTeam;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel WorksystemAssignTeamSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/worksystem', 'Worksystem Assign Teams');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-assign-team-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/worksystem', 'Create Worksystem Assign Team'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'team_id',
                'value' => function($model){
                    /* @var $model WorksystemAssignTeam */
                    return !empty($model->team_id) ? $model->team->name : null;
                },
            ],
            [
                'attribute' => 'user_id',
                'value' => function($model){
                    /* @var $model WorksystemAssignTeam */
                    return !empty($model->user_id) ? $model->user->nickname : null;
                },
            ],
            'des:ntext',
            'index',
            // 'is_delete',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
