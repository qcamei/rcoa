<?php

use common\wskeee\filemanage\FileManageAsset;
use wskeee\filemanage\models\FileManage;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel common\wskeee\filemanage\models\searchs\FileManageDetailSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/fileManage', 'File Manages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-manage-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa/fileManage', 'Create File Manage'), ['/filemanage/default/create'], ['class' => 'btn btn-success']) ?>
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
                    return $model->type == FileManage::FM_FILE ? '文档' : '目录';
                }
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'content' => function ($model){
                    return '<div class="course-name"><span>'.$model->name.'</span></div>' ;
                }
            ],
            //'name',
            [
                'attribute' => 'pid',
                'format' => 'raw',
                'value' => function ($model){
                    return !isset($model->pid) ? '<span style="color:red">Null</span>' : $model->fileManagePid->name;
                }
            ],
            'keyword',
            // 'icon',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
                            ['/filemanage/detail/view', 'id' => $model->id], $options);
                    },
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
                            ['/filemanage/default/update', 'id' => $model->id], $options);
                    },
                    'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'),
                            'data-pjax' => '0',
                            'data' => [
                                'confirm' => Yii::t('rcoa/fileManage', 'Are you sure you want to delete this item?'),
                                'method' => 'post'
                            ]
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                            ['/filemanage/default/delete', 'id' => $model->id], $options);
                    },       
                ],
                'template' => '{view}{update}{delete}',
            ],
        ],
    ]); ?>

</div>

<?php
    FileManageAsset::register($this);
?>