<?php

use common\models\expert\Expert;
use common\models\expert\ExpertType;
use common\models\expert\searchs\ExpertSearch;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ExpertSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa', 'Experts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name', 
                'label' => '名称',
                'value' => 'user.nickname'
            ],
            [
                'label' => '性别',
                'value' => function($model){
                    /* @var $model Expert */
                    return $model->user->sex == User::SEX_MALE ? "男" : "女";
                }
            ],
            [
                'attribute' => 'type',
                'label' => '专家类型',
                'value' => function($model){
                    /* @var $model Expert */
                    return $model->expertType->name;
                },
                'filter' => ArrayHelper::map(ExpertType::find()->all(), 'id', 'name')
            ],
            'job_title',
            'job_name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
