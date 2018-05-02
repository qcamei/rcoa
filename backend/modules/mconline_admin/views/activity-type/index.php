<?php

use common\models\mconline\searchs\McbsActivityTypeSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel McbsActivityTypeSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('null', '{Activity}{Type}{Administration}', [
            'Activity' => Yii::t('app', 'Activity'),
            'Type' => Yii::t('app', 'Type'),
            'Administration' => Yii::t('app', 'Administration'),
        ]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcbs-activity-type-index">

    <h1><?php //Html::encode($this->title)   ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('null', '{Create}{Activity}{Type}', [
                    'Create' => Yii::t('app', 'Create'),
                    'Activity' => Yii::t('app', 'Activity'),
                    'Type' => Yii::t('app', 'Type'),
                ]), ['create'], ['class' => 'btn btn-success'])
        ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            //'name',
            [
                'attribute' => 'name',
                'header' => Yii::t('app', 'Name'),
                'headerOptions' => [
                    'style' => [
                        'padding' => '8px'
                    ],
                ],
            ],
            //'des',
            [
                'attribute' => 'des',
                'header' => Yii::t('app', 'Des'),
                'headerOptions' => [
                    'style' => [
                        'padding' => '8px'
                    ],
                ],
            ],
            //'icon_path',
            [
                'label' => Yii::t('app', 'Icon'),
                'format' => 'raw',
                'header' => Yii::t('app', 'Icon'),
                'value' => function ($data){
                    return Html::img(MCONLINE_WEB_ROOT . $data['icon_path'], ['width' => '40', 'height' => '40']);
                },
                'headerOptions' => [
                    'style' => [
                        'width' => '100px',
                        'text-align' => 'center',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            //'created_at',
            // 'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Operating'),
                'headerOptions' => [
                    'style' => [
                        'width' => '80px',
                        'text-align' => 'center',
                    ],
                ],
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'vertical-align' => 'middle',
                    ],
                ],
            ],
        ],
    ]);?>
</div>
