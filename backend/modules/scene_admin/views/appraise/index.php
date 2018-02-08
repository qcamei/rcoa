<?php

use common\models\scene\SceneAppraiseTemplate;
use common\models\scene\SceneBookUser;
use common\models\scene\searchs\SceneAppraiseSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel SceneAppraiseSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa', 'Shoot Appraises');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shoot-appraise-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Create Question'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'role',
                'value' => function($model){
                    /* @var $model SceneAppraiseTemplate */
                    return SceneBookUser::$roleName[$model->role];
                },
                'options' => [
                    'class'=>'col-sm-2'
                ]
            ],
            [
                'label' => '题目',
                'value' => function($model){
                    /* @var $model SceneAppraiseTemplate */
                    return $model->question->title;
                },
            ],
            'value',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
            ],
    ],
    ]); ?>

</div>
