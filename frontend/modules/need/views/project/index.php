<?php

use wskeee\framework\models\searchs\ItemSearch;
use wskeee\rbac\components\ResourceHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ItemSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa', 'Profession ID');
?>
<div class="main project-index">

    <?= ResourceHelper::a(Yii::t('app', '{Create}{Item Child ID}', 
            ['Create' => Yii::t('app', 'Create'), 'Item Child ID' => Yii::t('app', 'Item Child ID')]), ['create'], ['class' => 'btn btn-success btn-margin-bottom']); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'tableOptions' => ['class' => 'table table-striped table-bordered','style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options'=>['style'=>'width:50px'],
            ],
            [
                'class' => 'frontend\modules\need\components\GridViewLinkCell',
                'attribute'=>'college',
                'url'=>'/need/college/view',
                'key'=>'college_id',
            ],
            [
                'class' => 'frontend\modules\need\components\GridViewLinkCell',
                'attribute'=>'name',
                'url'=>'/need/project/view'
            ],
            [
                'class' => 'common\components\RbacActionColumn',
                'options' => ['style'=>['width' => '70px']],
            ],
        ],
    ]); ?>
</div>
