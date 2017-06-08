<?php

use kartik\widgets\Select2;
use wskeee\rbac\models\AuthItem;
use wskeee\rbac\models\searchs\AuthItemSearch;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel AuthItemSearch */
/* @var $dataProvider ArrayDataProvider */

$this->title = '角色管理';
$this->params['breadcrumbs'][] = $this->title;

AuthItem::$category = $categorys;

?>
<div class="role-manager-index rbac">
    <p>
        <?= Html::a('创建权限', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    //Pjax::begin(['enablePushState'=>false]);
    
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => '所属模块',
                'attribute' => 'system_id',  
                'headerOptions' => ['style' => 'width: 240px;'],
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model AuthItem */
                    return isset(AuthItem::$category[$model->system_id]) ? AuthItem::$category[$model->system_id] : null;
                },
                'filter' => Select2::widget([
                    //'value' => null,
                    'model' => $searchModel,
                    'attribute' => 'system_id',
                    'data' => AuthItem::$category,
                    'hideSearch'=>true,
                    'options' => ['placeholder' => '请选择...']
                ])
            ],
            'name',
            'description:ntext',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => [
                    'style' => 'width: 75px'
                ],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        /* @var $model AuthItem */
                        $options = [
                            'class' => 'btn btn-primary',
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('编辑', 
                            ['view', 'name' => $model->name], $options);
                    },
                ],
                'template' => '{view}',
            ],
        ],
    ]); 
    //Pjax::end();
    ?>

</div>
