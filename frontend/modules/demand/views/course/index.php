<?php

use wskeee\framework\models\searchs\ItemSearch;
use wskeee\rbac\components\ResourceHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ItemSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/basedata', 'Course');
?>
<div class="container course-index">

    <p>
        <?= ResourceHelper::a(Yii::t('rcoa/basedata', '{Create} {Course}', 
                ['Create' => Yii::t('rcoa/basedata', 'Create'), 'Course' => Yii::t('rcoa/basedata', 'Course')]), ['create'], ['class' => 'btn btn-success']); ?>
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
                'class' => 'frontend\modules\demand\components\GridViewLinkCell',
                'attribute'=>'college',
                'url'=>'/demand/college/view',
                'key'=>'college_id',
            ],
            [
                'class' => 'frontend\modules\demand\components\GridViewLinkCell',
                'attribute'=>'project',
                'url'=>'/demand/project/view',
                'key'=>'project_id',
            ],
            [
                'class' => 'frontend\modules\demand\components\GridViewLinkCell',
                'attribute'=>'name',
                'url'=>'/demand/course/view'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options'=>['style'=>'width:70px'],
                'visibleButtons' => [
                    'create' => $rbac['create'],
                    'update' => $rbac['update'],
                    'delete' => $rbac['delete'],
                ],
            ],
        ],
    ]); ?>
</div>