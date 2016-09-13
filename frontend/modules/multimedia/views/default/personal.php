<?php

use common\models\multimedia\searchs\MultimediaManageSearch;
use frontend\modules\multimedia\MultimediaAsset;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel MultimediaManageSearch */
/* @var $dataProvider ActiveDataProvider */

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

<?php
$js = 
<<<JS
    $('#submit').click(function()
    {
        $('#item-manage-form').submit();
    });
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    MultimediaAsset::register($this);
?>