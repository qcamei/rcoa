<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel wskeee\framework\models\searchs\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/framework', 'Projects');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="framework-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <?= Html::a(Yii::t('rcoa/framework', 'Create Project'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute' => 'parent.name',
                'label' => '所属项目',
                'headerOptions' => ['class'=>'col-lg-4']
            ],
            'name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
