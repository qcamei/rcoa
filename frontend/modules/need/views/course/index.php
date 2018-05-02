<?php

use wskeee\framework\models\searchs\ItemSearch;
use wskeee\rbac\components\ResourceHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ItemSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Courses');
?>
<div class="main course-index">

    <?= ResourceHelper::a(Yii::t('app', '{Create}{Courses}', 
            ['Create' => Yii::t('app', 'Create'), 'Courses' => Yii::t('app', 'Courses')]), ['create'], ['class' => 'btn btn-success btn-margin-bottom']); ?>

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
                'attribute'=>'project',
                'url'=>'/need/project/view',
                'key'=>'project_id',
            ],
            [
                'class' => 'frontend\modules\need\components\GridViewLinkCell',
                'attribute'=>'name',
                'url'=>'/need/course/view'
            ],
            [
                'class' => 'common\components\RbacActionColumn',
                'options' => ['style'=>['width' => '70px']],
            ],
        ],
    ]); ?>
</div>