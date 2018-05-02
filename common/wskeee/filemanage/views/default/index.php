<?php

use wskeee\filemanage\models\FileManage;
use wskeee\filemanage\models\searchs\FileManageSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel FileManageSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/fileManage', 'File Manages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-manage-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/fileManage', 'Create File Manage'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function ($model){
                    return $model->getTypeName();
                }
            ],
            'name',
            [
                'attribute' => 'pid',
                'format' => 'raw',
                'value' => function ($model){
                    return !isset($model->pid) ? '<span style="color:red">Null</span>' : $model->fileManagePid->name;
                }
            ],
            'keyword',
            //'file_link',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
