<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchs\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '{Company}{List}',[
    'Company' => Yii::t('app', 'Company'),
    'List' => Yii::t('app', 'List'),
]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <p>
        <?= Html::a(Yii::t('app', '{Create}{Company}',[
            'Create' => Yii::t('app', 'Create'),
            'Company' => Yii::t('app', 'Company'),
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            [
//                'attribute' => 'id',
//                'headerOptions' => [
//                    'style' => [
//                        'width' => '80px'
//                    ],
//                ],
//            ],
            [
                'attribute' => 'name',
                'headerOptions' => [
                    'style' => [
                        'min-width' => '160px'
                    ],
                ],
            ],
            [
                'attribute' => 'logo',
                'filter' => false,
                'format' => 'raw',
                'value' => function ($data) {
                    return !empty($data->logo) ? Html::img(WEB_ROOT . $data->logo) : null;
                },
            ],
            'des',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
