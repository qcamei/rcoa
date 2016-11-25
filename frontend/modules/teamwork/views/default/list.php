<?php

use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\utils\TeamworkTool;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

/* @var $this View */
/* @var $model ItemManage */
/* @var $twTool TeamworkTool */
/* @var $dataProvider ActiveDataProvider */


$this->title = Yii::t('rcoa/teamwork', 'Item Manages');
$this->params['breadcrumbs'][] = $this->title;

$courseManages = ArrayHelper::getColumn($dataProvider->allModels, 'courseManages');
$courseIds = [];
foreach ($courseManages as $elements) {
    foreach ($elements as $value)
        $courseIds[] = $value->id;
}

ItemManage::$progress = ArrayHelper::map($twTool->getItemProgress($courseIds)->all(), 'id', 'progress');

?>
<div class="container item-manage-list item-manage">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search-box"> 
        <?php $form = ActiveForm::begin([
            'id' => 'item-manage-search',
            'action' => ['search'],
            'method' => 'get',
        ]); ?>
        <div class="search-text-input">
            <?= Html::textInput('keyword', !empty($keyword)? $keyword : '', [
                'class' => 'form-control search-input',
                'placeholder' => '请输入关键字...'
            ]); ?>
        </div>
        <?php ActiveForm::end(); ?>  
        <div class="search-btn-bg">
            <?= Html::a(Yii::t('rcoa', 'Search'), 'javascript:;', ['id' => 'submit', 'class' => 'btn', 'style' => 'float: left; width:100%']); ?>
        </div>
    </div>
    <div id="item-manage-list">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'summaryOptions' => [
                //'class' => 'summary',
                'class' => 'hidden',
                //'style' => 'float: left'
            ],
            'pager' => [
                'options' => [
                    //'class' => 'pagination',
                    'class' => 'hidden',
                    //'style' => 'float: right; margin: 0px;'
                ]
            ],
            'tableOptions' => ['class' => 'table table-striped table-list'],
            'columns' => [
                [
                    'class' => 'frontend\modules\teamwork\components\ItemListTd',
                    'label' => Yii::t('rcoa/teamwork', 'Item Type'),
                    'value'=> function($model){
                        /* @var $model ItemManage */
                        return !empty($model->item_type_id) ? $model->itemType->name : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '185px'  
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
                        return !empty($model->item_id) ? $model->item->name : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '205px' 
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
                            return '<div class="course-name">'.(!empty($model->item_child_id) ? $model->itemChild->name : null).'</div>'.
                                Html::beginTag('div', [
                                    'class' => 'progress table-list-progress',
                                    'style' => 'height:12px;margin:2px 0;border-radius:0px;'
                                ]).Html::beginTag('div', [
                                        'class' => 'progress-bar', 
                                        'style' => 'width:'.ItemManage::$progress[$model->id].'%;line-height: 12px;font-size: 10px;',
                                    ]).ItemManage::$progress[$model->id].'%'.
                                    Html::endTag('div').
                                Html::endTag('div');
                        },
                    'headerOptions' => [
                        'style' => [
                            'max-width' => '380px',
                            'min-width' => '84px',
                        ],
                    ],
                    'contentOptions' =>[
                        'class' => 'course-name',
                        'style' => [
                            'max-width' => '380px', 
                            'max-width' => '84px',
                            'padding' => '2px 8px',
                        ],
                    ],
                ],
                /*[
                    'class' => 'frontend\modules\teamwork\components\ItemListTd',
                    'label' => Yii::t('rcoa/teamwork', 'Course'),
                    'format' => 'raw',
                    'value' => function($model){
                        /* @var $model ItemManage *
                        return count($model->courseManages).' 门';
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '100px',
                        ],
                    ],
                    'contentOptions' =>[
                        'class' => 'hidden-xs',
                    ],

                ],*/
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
        ]);?>
        
        <div class="summary">总共<b><?= $count ?></b>条数据</div>
        
        <?= LinkPager::widget([  
            'pagination' => new Pagination([
                'totalCount' => $count,  
            ]),  
        ]) ?> 
    </div>
</div>

<?= $this->render('_footer',[
    'twTool' => $twTool,
]); ?>

<?php
$url = Yii::$app->urlManager->createUrl(['teamwork/default/search']);
$js = 
<<<JS
    $('#submit').click(function(){
        $('#item-manage-search').submit();
    });
JS;
    $this->registerJs($js,  View::POS_READY);
?>