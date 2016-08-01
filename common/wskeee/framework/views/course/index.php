<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel wskeee\framework\models\searchs\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/framework', 'Courses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <?= Html::a(Yii::t('rcoa/framework', 'Create Course'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'parent.parent.name',
                'label' => '所属项目',
                'headerOptions' => ['class'=>'col-lg-2']
            ],
            [
                'attribute' => 'parent.name',
                'label' => '所属子项目',
                'headerOptions' => ['class'=>'col-lg-2']
            ],
            'name',

            ['class' => 'yii\grid\ActionColumn', 'headerOptions' => ['class'=>'col-lg-1']],
        ],
    ]); ?>

</div>
