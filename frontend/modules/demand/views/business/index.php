<?php

use frontend\modules\demand\assets\BasedataAssets;
use wskeee\framework\models\searchs\ItemTypeSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ItemTypeSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/basedata', 'Item Types');
?>
<div class="container item-type-index">

    <p>
        <?php
        if ($rbac['create']) {
            echo Html::a(
                    Yii::t('rcoa/basedata', '{Create} {Item Type}', ['Create' => Yii::t('rcoa/basedata', 'Create'), 'Item Type' => Yii::t('rcoa/basedata', 'Item Type')]), ['create'], ['class' => 'btn btn-success']);
        }
        ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered','style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['style'=>['width' => '20px']]
            ],
            [
                'class' => 'frontend\modules\demand\components\GridViewLinkCell',
                'attribute'=>'name',
                'url'=>'/demand/business/view'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['style'=>['width' => '30px']],
                'visibleButtons' => [
                    'create' => $rbac['create'],
                    'update' => $rbac['update'],
                    'delete' => $rbac['delete'],
                ],
            ],
        ],
    ]); ?>
</div>
