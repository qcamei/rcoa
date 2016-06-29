<?php

use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TwAsset;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
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
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '项目类型',
                'filter' => Html::a('lll'),
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
                'value' => function($model){
                        /* @var $model ItemManage */
                        return $model->itemChild->name;
                    }
                ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '进度',
                'format' => 'raw',
                'value' => function($model){
                    /* @var $model ItemManage */
                    return Html::beginTag('div', ['class' => 'progress', 'style' => 'height:20px;margin-bottom:0px;']).
                                Html::beginTag('div', [
                                    'class' => 'progress-bar', 
                                    'role' => 'progressbar', 
                                    'aria-valuenow' => $model->progress,
                                    'aria-valuemin' => '0',
                                    'aria-valuemax' => '100',
                                    'style' => 'width:'.$model->progress.'%'//$model->progress.'%',
                                ]).
                                $model->progress.'%'.
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

<div class="controlbar footer-item-index" style="height: 60px;padding-top:0px; ">
    <div class="container">
        <?php
         var_dump( Yii::$app->controller->getRoute());exit;
            
                Html::a(Html::img(['/filedata/image/home_64x64.png']).'主页', 'javascript:;', ['class' => 'footer-item',]);
                Html::a(Html::img(['/filedata/image/project_64x64.png']).'项目', ['index'], ['class' => 'footer-item']);
                Html::a(Html::img(['/filedata/image/course_64x64.png']).'课程', ['type', 'id' => ''], ['class' => 'footer-item']);
                Html::a(Html::img(['/filedata/image/statistics_64x64.png']).'统计', ['type', 'id' => ''], ['class' => 'footer-item']);
                Html::a(Html::img(['/filedata/image/new_64px64.png']).'创建项目', ['create'], [
                'class' => $model->isLeader() ? 'footer-item footer-item-right' : 'footer-item footer-item-right disabled']);
        ?>
    </div>
</div>

<?php
    TwAsset::register($this);
?>