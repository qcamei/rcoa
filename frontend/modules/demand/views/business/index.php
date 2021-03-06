<?php

use wskeee\framework\models\searchs\ItemTypeSearch;
use wskeee\rbac\components\ResourceHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ItemTypeSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = '基础数据';//Yii::t('rcoa/basedata', 'Item Types');
?>
<div class="container item-type-index">

    <p>
        <?= ResourceHelper::a(Yii::t('rcoa/basedata', '{Create} {Item Type}', 
                ['Create' => Yii::t('rcoa/basedata', 'Create'), 'Item Type' => Yii::t('rcoa/basedata', 'Item Type')]), ['create'], ['class' => 'btn btn-success']); ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered','style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['style'=>['width' => '50px']]
            ],
            [
                'class' => 'frontend\modules\demand\components\GridViewLinkCell',
                'attribute'=>'name',
                'url'=>'/demand/business/view'
            ],
            [
                'class' => 'common\components\RbacActionColumn',
                'options' => ['style'=>['width' => '70px']],
            ],
        ],
    ]); ?>
</div>
