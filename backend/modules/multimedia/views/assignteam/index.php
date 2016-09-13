<?php

use common\models\multimedia\MultimediaAssignTeam;
use common\models\multimedia\searchs\MultimediaAssignTeamSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel MultimediaAssignTeamSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/multimedia', 'Multimedia Assign Teams');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="multimedia-assign-team-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/multimedia', 'Create Multimedia Assign Team'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'team_id',
                'value' => function($model){
                    /* @var $model MultimediaAssignTeam*/
                    return $model->team->name;
                }
            ],
            [
                'attribute' => 'u_id',
                'value' => function($model){
                    /* @var $model MultimediaAssignTeam*/
                    return $model->assignUser->nickname;
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
