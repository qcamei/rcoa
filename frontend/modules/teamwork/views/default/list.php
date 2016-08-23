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
<div class="container item-manage-list item-manage">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height: 35px;border: 1px #ccc solid;margin-top: 5px;border-radius: 5px;padding: 0px;">
        <i style="background: url('/filedata/teamwork/image/search-icon.png');width: 16px;height: 16px;display: block;margin-top: 8px;margin-left: 5px;float: left;"></i>
        <?= Html::textInput('', '', [
            'class' => 'col-lg-10 col-md-10 col-sm-10 col-xs-8',
            'style' => 'float: left;height: 32px;border: 0px;padding: 8px;margin-left: 5px;padding: 0px;',
            'placeholder' => '该功能待开发中...'
        ])?>
        <?= Html::img(['/filedata/teamwork/image/20160718152550.jpg'], [
            
            'style' => 'height: 32px;float: right; padding: 0px;"'
        ])?>
       
    </div>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => Yii::t('rcoa/teamwork', 'Item Type'),
                'value'=> function($model){
                    /* @var $model ItemManage */
                    return $model->itemType->name;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '226px'  
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => Yii::t('rcoa/teamwork', 'Item'),
                'value' => function ($model){
                    /* @var $model ItemManage */
                    return $model->item->name;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '325px' 
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => Yii::t('rcoa/teamwork', 'Item Child'),
                'format' => 'raw',
                'content' => function($model){
                        /* @var $model ItemManage */
                        return '<div class="course-name">'.$model->itemChild->name.'</div>'.
                            Html::beginTag('div', [
                                'class' => 'progress table-list-progress',
                                'style' => 'height:12px;margin:2px 0;border-radius:0px;'
                            ]).Html::beginTag('div', [
                                    'class' => 'progress-bar', 
                                    'style' => 'width:'.(int)($model->progress * 100).'%;line-height: 12px;font-size: 10px;',
                                ]).
                                (int)($model->progress * 100).'%'.
                                Html::endTag('div').
                            Html::endTag('div');
                    },
                'headerOptions' => [
                    'style' => [
                        'max-width' => '400px',
                        'min-width' => '84px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'course-name',
                    'style' => [
                        'max-width' => '400px', 
                        'max-width' => '84px',
                        'padding' => '2px 8px',
                    ],
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => Yii::t('rcoa/teamwork', 'Course'),
                'format' => 'raw',
                'value' => function($model){
                    /* @var $model ItemManage */
                    return count($model->courseManages).' 门';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '74px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'hidden-xs',
                ],
               
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemActBtnCol',
                'label' => Yii::t('rcoa', 'Operating'),
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
    'twTool' => $twTool,
]); ?>