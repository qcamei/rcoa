<?php

use common\components\GridViewChangeSelfColumn;
use kartik\widgets\Select2;
use wskeee\webuploader\models\searchs\UploadfileSearch;
use wskeee\webuploader\models\Uploadfile;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel UploadfileSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t(null, '{File}{List}', [
            'File' => Yii::t('app', 'File'),
            'List' => Yii::t('app', 'List')
        ]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uploadfile-index">

    <h1><?php //var_dump($dataProvider);exit;?></h1>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => ['width' => '30px'],
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'attribute' => 'name',
                'label' => Yii::t(null, '{File}{Name}', [
                    'File' => Yii::t('app', 'File'),
                    'Name' => Yii::t('app', 'Name')
                ]),
                'format' => 'raw',
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'padding' => '8px'
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['filename']) ? $data['filename'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td',
                ],
            ],
            [
                'attribute' => 'created_by',
                'label' => Yii::t('app', 'Upload By'),
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_by',
                    'data' => $uploadBy,
                    'hideSearch' => false,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'headerOptions' => [
                    'style' => [
                        'width' => '200px',
                        'text-align' => 'center',
                        'padding' => '8px'
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['created_by']) ? $data['created_by'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td',
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'attribute' => 'del_mark',
                'label' => Yii::t(null, '{Is}{Mark}', [
                    'Is' => Yii::t('app', 'Is'),
                    'Mark' => Yii::t('app', 'Mark'),
                ]),
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'del_mark',
                    'data' => Uploadfile::$TYPES,
                    'hideSearch' => true,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'headerOptions' => [
                    'style' => [
                        'width' => '200px',
                        'text-align' => 'center',
                        'padding' => '8px'
                    ],
                ],
                'class' => GridViewChangeSelfColumn::className(),
                'contentOptions' => [
                    'class' => 'activity-name list-td',
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'attribute' => 'is_del',
                'label' => Yii::t('null', '{Already}{Delete}',[
                    'Already' => Yii::t('app', 'Already'),
                    'Delete' => Yii::t('app', 'Delete'),
                ]),
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'is_del',
                    'data' => Uploadfile::$TYPES,
                    'hideSearch' => true,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'headerOptions' => [
                    'style' => [
                        'width' => '200px',
                        'text-align' => 'center',
                        'padding' => '8px'
                    ],
                ],
               'value' => function($data) {
                    return $data['is_del'] ? '<span style="color:red">是</span>' : '否';
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td',
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'header' => Yii::t('app', 'Operating'),
                'headerOptions' => [
                    'style' => [
                        'width' => '80px',
                        'text-align' => 'center',
                    ],
                ],
                'format' => 'raw',
                'value' => function( $data) {
                    return Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $data['id']], [
                           'class' => 'btn btn-danger btn-sm',
                            'data' => [
                               'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                               'method' => 'post'
                            ],
                    ]);
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
        ],
    ]);
    ?>
</div>
