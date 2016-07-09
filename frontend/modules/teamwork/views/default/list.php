<?php

use common\models\teamwork\ItemManage;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ItemManage */
/* @var $dataProvider ActiveDataProvider */


$this->title = Yii::t('rcoa/teamwork', 'Item Manages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container item-manage-list has-title item-manage">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '项目类型',
                'value'=> function($model){
                    /* @var $model ItemManage */
                    return $model->itemType->name;
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '项目名称',
                'value' => function ($model){
                    /* @var $model ItemManage */
                    return $model->item->name;
                },
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '子项目',
                'format' => 'raw',
                'value' => function($model){
                        /* @var $model ItemManage */
                        return Html::a($model->itemChild->name, ['view','id' => $model->id], [
                            'style' => 'color:#000',
                        ]);
                    }
                ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '进度',
                'format' => 'raw',
                'value' => function($model){
                    /* @var $model ItemManage */
                    return Html::beginTag('div', ['class' => 'progress table-list-progress']).
                                Html::beginTag('div', [
                                    'class' => 'progress-bar', 
                                    'style' => 'width:'.(int)($model->progress * 100).'%',
                                ]).
                                (int)($model->progress * 100).'%'.
                                Html::endTag('div').
                            Html::endTag('div');
                }
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemActBtnCol',
                'label' => '操作',
                'contentOptions' =>[
                    'style'=> [
                        'width' => '90px',
                        'padding' =>'4px',
                    ],
                 ],
                 'headerOptions'=>[
                    'style'=> [
                        'width' => '125px',
                    ]
                ],
            ],
        ],
    ]); ?>
</div>

<?= $this->render('_footer',[
    'model' => $model,
]); ?>