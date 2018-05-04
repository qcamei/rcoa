<?php

use wskeee\framework\models\searchs\ItemTypeSearch;
use wskeee\rbac\components\ResourceHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ItemTypeSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Basedata');

?>

<div class="main item-type-index">
    
    <?= ResourceHelper::a(Yii::t('app', '{Create}{Item Type ID}', 
            ['Create' => Yii::t('app', 'Create'), 'Item Type ID' => Yii::t('app', 'Item Type ID')]), ['create'], ['class' => 'btn btn-success btn-margin-bottom']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'tableOptions' => ['class' => 'table table-striped table-bordered','style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['style'=>['width' => '50px']]
            ],
            [
                'class' => 'frontend\modules\need\components\GridViewLinkCell',
                'attribute'=>'name',
                'url'=>'/need/business/view'
            ],
            [
                'class' => 'common\components\RbacActionColumn',
                'options' => ['style'=>['width' => '70px']],
            ],
        ],
    ]); ?>
</div>
