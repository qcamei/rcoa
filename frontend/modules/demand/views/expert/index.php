<?php

use common\models\expert\searchs\ExpertSearch;
use wskeee\rbac\components\ResourceHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ExpertSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/basedata', 'Expert');
?>
<div class="container expert-index">

    <p>
        <?= ResourceHelper::a(Yii::t('rcoa/basedata', '{Create} {Expert}', 
                ['Create' => Yii::t('rcoa/basedata', 'Create'), 'Expert' => Yii::t('rcoa/basedata', 'Expert')]), ['create'], ['class' => 'btn btn-success']); ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered','style' => ['table-layout' => 'fixed']],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options'=>['style'=>'width:50px'],
            ],
            [
                'attribute'=>'personal_image',
                'options'=>['style'=>'width:60px'],
                'format'=>['Image',['style'=>'width:40px;height:40px;']],
            ],
            [
                'attribute'=>'username',
                'value'=>'user.username',
                'options'=>['style'=>'width:100px'],
            ],
            [
                'attribute'=>'nickname',
                'options'=>['style'=>'width:100px'],
            ],
            'employer',
            'job_title',
            [
                'class' => 'common\components\RbacActionColumn',
                'options' => ['style'=>['width' => '70px']],
            ],
        ],
    ]); ?>
</div>