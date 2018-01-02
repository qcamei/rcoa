<?php

use common\models\scene\searchs\SceneBookSearch;
use frontend\modules\scene\SceneAsset;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel SceneBookSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Scene Books');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-book-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Scene Book'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'site_id',
            'date',
            'time_index:datetime',
            'status',
            // 'business_id',
            // 'level_id',
            // 'profession_id',
            // 'course_id',
            // 'lession_time:datetime',
            // 'content_type',
            // 'shoot_mode',
            // 'is_photograph',
            // 'camera_count',
            // 'start_time',
            // 'remark',
            // 'is_transfer',
            // 'teacher_id',
            // 'booker_id',
            // 'created_by',
            // 'created_at',
            // 'updated_at',
            // 'ver',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php
$js = <<<JS
   
    
JS;
$this->registerJs($js, View::POS_READY);
?>

<?php
    SceneAsset::register($this);
?>
