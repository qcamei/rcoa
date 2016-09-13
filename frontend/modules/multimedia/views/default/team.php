<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\multimedia\searchs\MultimediaManageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('rcoa/multimedia', 'Multimedia Manages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="multimedia-manage-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/multimedia', 'Create Multimedia Manage'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'item_type_id',
            'item_id',
            'item_child_id',
            'course_id',
            // 'name',
            // 'video_length',
            // 'workload',
            // 'proportion',
            // 'content_type',
            // 'carry_out_time',
            // 'level',
            // 'make_team',
            // 'status',
            // 'path',
            // 'create_team',
            // 'create_by',
            // 'created_at',
            // 'updated_at',
            // 'des',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
