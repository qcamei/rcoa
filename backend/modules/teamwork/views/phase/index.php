<?php

use common\models\teamwork\Phase;
use common\models\teamwork\searchs\PhaseSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Phase*/
/* @var $searchModel PhaseSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/teamwork', 'Phases');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phase-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/teamwork', 'Create Phase'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('rcoa/teamwork', 'Template Types'), ['type/index'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'template_type_id',
                'value' => function($model){
                    /* @var $model Phase*/
                    return $model->templateType->name;
                }
            ],
            //'template_type_id',
            'name',
            'weights',
            //'progress',
            /*[
                'attribute' => 'create_by',
                'value' => function($model){
                    return $model->createBy->nickname;
                }
            ],*/
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
