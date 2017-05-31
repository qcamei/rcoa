<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\rbac\models\searchs\AuthItem */
/* @var $dataProvider yii\data\ArrayDataProvider */

$this->title = '用户角色';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'nickname',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => [
                    'style' => 'width: 75px'
                ],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'class' => 'btn btn-primary',
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('分配', 
                            ['view', 'id' => $model->id], $options);
                    },
                ],
                'template' => '{view}',
            ],
        ],
    ]); ?>

</div>
