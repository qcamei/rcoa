<?php

use common\models\mconline\searchs\McbsCourseSearch;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel McbsCourseSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t(null, '{Mcbs}{Courses}', [
                'Mcbs' => Yii::t('app', 'Mcbs'),
                'Courses' => Yii::t('app', 'Courses'),
            ]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcbs-course-index mcbs default-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t(null, '{Mcbs}{Courses}', [
                        'Mcbs' => Yii::t('app', 'Mcbs'),
                        'Courses' => Yii::t('app', 'Courses'),
                    ]), ['create'], ['class' => 'btn btn-success']) ?>
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
            // 'create_by',
            // 'status',
            // 'is_publish',
            // 'publish_time',
            // 'close_time',
            // 'des:ntext',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>


<?php
$js = 
<<<JS
        
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>