<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\worksystem\searchs\WorksystemContentinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/worksystem', 'Worksystem Contentinfos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-contentinfo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/worksystem', 'Create Worksystem Contentinfo'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'worksystem_task_id',
            'worksystem_content_id',
            'price',
            'budget_number',
            // 'budget_cost',
            // 'reality_number',
            // 'reality_cost',
            // 'index',
            // 'is_delete',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
