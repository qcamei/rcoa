<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel wskeee\framework\models\searchs\PhaseLinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/framework', 'Phase Links');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phase-link-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/framework', 'Create Phase Link'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'phases_id',
            'link_id',
            'total',
            'completed',
            'progress',
            // 'create_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
