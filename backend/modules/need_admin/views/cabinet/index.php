<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\workitem\searchs\WorkitemCabinetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/workitem', 'Workitem Cabinets');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workitem-cabinet-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/workitem', 'Create Workitem Cabinet'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'workitem_id',
            'index',
            'name',
            'title',
            // 'type',
            // 'path',
            // 'content',
            // 'is_deleted',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
