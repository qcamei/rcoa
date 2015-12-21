<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\shoot\searchs\ExpertProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa', 'Expert Projects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-project-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Create Expert Project'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'expert_id',
            'project_id',
            'start_time:datetime',
            'end_time:datetime',
            // 'cost',
            // 'compatibility',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
