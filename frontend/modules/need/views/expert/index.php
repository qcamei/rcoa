<?php

use common\models\expert\searchs\ExpertSearch;
use wskeee\rbac\components\ResourceHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ExpertSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Expert');

?>
<div class="main expert-index">

    <p>
        <?= ResourceHelper::a(Yii::t('app', '{Create}{Expert}', 
                ['Create' => Yii::t('app', 'Create'), 'Expert' => Yii::t('app', 'Expert')]), ['create'], ['class' => 'btn btn-success']); ?>
    </p>
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
                'attribute'=>'personal_image',
                'options'=>['style'=>'width:60px'],
                'format'=>['Image',['style'=>'width:40px;height:40px;']],
            ],
            [
                'attribute'=>'username',
                'value'=>'user.username',
                'options'=>['style'=>'width:115px'],
            ],
            [
                'attribute'=>'nickname',
                'options'=>['style'=>'width:80px'],
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