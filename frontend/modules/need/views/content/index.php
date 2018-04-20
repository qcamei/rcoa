<?php

use common\models\need\searchs\NeedContentSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel NeedContentSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Need Contents');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="need-content-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{summary}\n{pager}",
        'summaryOptions' => ['class' => 'hidden'],
        'pager' => [
            'options' => ['class' => 'hidden']
        ],
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                //'attribute' => 'workitem_id',
                'label' => Yii::t('app', 'Business ID'),
                'format' => 'raw',
//                'value'=> function($model){
//                    /* @var $model NeedTask */
//                    return $model->level ? '<i class="fa fa-bolt">' : '';
//                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '16px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        //'td' => 'hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            'workitem_type_id',
            'workitem_id',
            'is_new',
            //'price',
            //'plan_num',
            //'reality_num',
            //'sort_order',
            //'is_del',
            //'created_by',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
