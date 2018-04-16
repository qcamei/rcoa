<?php

use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;
use common\modules\webuploader\models\searchs\UploadfileSearch;
use common\modules\webuploader\models\Uploadfile;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel UploadfileSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t(null, '{File}{List}{Administration}', [
            'File' => Yii::t('app', 'File'),
            'List' => Yii::t('app', 'List'),
            'Administration' => Yii::t('app', 'Administration'),
        ]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uploadfile-index">

    <p>
        <?= Html::a(Yii::t('null', '{Upload}{File}', [
                    'Upload' => Yii::t('app', 'Upload'),
                    'File' => Yii::t('app', 'File'),
                ]), ['create'], ['class' => 'btn btn-success'])
        ?>
    </p>
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
                'attribute' => 'id',
                'label' => Yii::t(null, '{File}{ID}', [
                    'File' => Yii::t('app', 'File'),
                    'ID' => Yii::t('app', 'ID')
                ]),
                'format' => 'raw',
                'headerOptions' => [
                    'style' => [
                        'min-width' => '150px',
                        'text-align' => 'center',
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['id']) ? $data['id'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'list-td',
                    'style' => [
                        'text-align' => 'center',
                        'word-break'=> 'break-word'
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
                        'min-width' => '150px',
                        'text-align' => 'center',
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['filename']) ? $data['filename'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'list-td',
                    'style' => [
                        'word-break'=> 'break-word',
                        'text-align' => 'center',
                    ]
                ],
            ],
            [
                'attribute' => 'path',
                'label' => Yii::t(null, '{File}{Path}', [
                    'File' => Yii::t('app', 'File'),
                    'Path' => Yii::t('app', 'Path')
                ]),
                'format' => 'raw',
                'headerOptions' => [
                    'style' => [
                        'min-width'=> '280px',
                        'text-align' => 'center',
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['path']) ? $data['path'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'list-td',
                    'style' => [
                        'word-break'=> 'break-word',
                        'text-align' => 'center',
                    ]
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
                        'text-align' => 'center',
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['created_by']) ? $data['created_by'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'list-td',
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'attribute' => 'created_at',
                'label' => Yii::t(null, '{Upload}{Time}', [
                    'Upload' => Yii::t('app', 'Upload'),
                    'Time' => Yii::t('app', 'Time')
                ]),
                'format' => 'raw',
                'filter' => DateRangePicker::widget([    // 日期组件
                    'model' => $searchModel,
                    'name' => 'time',
                    'value' => ArrayHelper::getValue($params, 'time'),
                    'hideInput' => true,
                    'convertFormat'=>true,
                    'pluginOptions' => [
                        'locale'=>['format' => 'Y-m-d'],
                        'allowClear' => true,
                    ],
                ]),
                'headerOptions' => [
                    'style' => [
                        'min-width' => '240px',
                        'text-align' => 'center',
                    ],
                ],
                'value' => function($data) {
                    return !empty(date('Y-m-d H:i', $data['created_at'])) ? date('Y-m-d H:i', $data['created_at']) : NULL;
                },
                'contentOptions' => [
                    'class' => 'list-td',
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
//            [
//                'attribute' => 'del_mark',
//                'label' => Yii::t(null, '{Is}{Mark}', [
//                    'Is' => Yii::t('app', 'Is'),
//                    'Mark' => Yii::t('app', 'Mark'),
//                ]),
//                'format' => 'raw',
//                'filter' => Select2::widget([
//                    'model' => $searchModel,
//                    'attribute' => 'del_mark',
//                    'data' => Uploadfile::$TYPES,
//                    'hideSearch' => true,
//                    'options' => ['placeholder' => Yii::t('app', 'All')],
//                    'pluginOptions' => [
//                        'allowClear' => true,
//                    ],
//                ]),
//                'headerOptions' => [
//                    'style' => [
//                        'width' => '200px',
//                        'text-align' => 'center',
//                    ],
//                ],
//                'class' => GridViewChangeSelfColumn::className(),
//                'contentOptions' => [
//                    'class' => 'list-td',
//                    'style' => [
//                        'text-align' => 'center',
//                    ],
//                ],
//            ],
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
                        'text-align' => 'center',
                    ],
                ],
               'value' => function($data) {
                    return $data['is_del'] ? '<span style="color:red">是</span>' : '否';
                },
                'contentOptions' => [
                    'class' => 'list-td',
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'header' => Yii::t('app', 'Operating'),
                'headerOptions' => [
                    'style' => [
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
