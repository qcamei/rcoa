<?php

use common\components\GridViewChangeSelfColumn;
use common\models\searchs\SystemSearch;
use common\models\System;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel SystemSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Systems');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-index">

    <p>
        <?= Html::a(Yii::t('app', '{Create}{Systems}',[
            'Create' => Yii::t('app', 'Create'),
            'Systems' => Yii::t('app', 'Systems'),
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'aliases',
            //'module_image',
            'module_link',
            'des',
            // 'isjump',
            'index',
            [
                'attribute' => 'parent_id',
                'value' => function($model){
                    /* @var $model System */
                    return !empty($model->parent_id) ? $model->parent->name : null;
                },
            ],
            [
                'attribute' => 'is_delete',
                'label' => Yii::t('app', 'Is Deleted'),
                'class' => GridViewChangeSelfColumn::class,
                'plugOptions'=>[
                    'values' => ['N','Y'],
                ],
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ]
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>