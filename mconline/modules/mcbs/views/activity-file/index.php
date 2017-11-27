<?php

use common\models\mconline\McbsCourse;
use common\models\mconline\searchs\McbsActivityFileSearch;
use kartik\widgets\Select2;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel McbsActivityFileSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $couModel McbsCourse */

$this->title = Yii::t('null', '{File}{List}', [
            'File' => Yii::t('app', 'File'),
            'List' => Yii::t('app', 'List'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Mcbs}{Courses}', [
        'Mcbs' => Yii::t('app', 'Mcbs'),
        'Courses' => Yii::t('app', 'Courses'),
    ]), 'url' => ['default/']];
$this->params['breadcrumbs'][] = ['label' => $couModel->course->name, 'url' => ['default/view', 'id' => $couModel->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcbs-activity-file-index">

    <p>
        <?= Html::a(Yii::t('app', 'Back'), ['default/view' . '?id=' . $couModel->id], ['class' => 'btn btn-default']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider['dataProvider'],
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'tableOptions' => ['class' => 'table table-striped table-list'],
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
                'attribute' => 'chapter_name',
                'label' => Yii::t(null, '{Belong}{Chapter}', [
                    'Belong' => Yii::t('app', 'Belong'),
                    'Chapter' => Yii::t('app', 'Chapter')
                ]),
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'name' => 'chapter_id',
                    'value' => ArrayHelper::getValue($dataProvider['filter'], 'chapter_id'),
                    'data' => $belongChapter,
                    'hideSearch' => false,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'filterOptions' => [
                    'class' => 'hidden-xs hidden-sm',
                ],
                'headerOptions' => [
                    'class' => [
                        'th' => 'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'padding' => '8px'
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['chapter_name']) ? $data['chapter_name'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td hidden-xs hidden-sm',
                ],
            ],
            [
                'attribute' => 'section_name',
                'label' => Yii::t(null, '{Belong}{Section}', [
                    'Belong' => Yii::t('app', 'Belong'),
                    'Section' => Yii::t('app', 'Section')
                ]),
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'name' => 'section_id',
                    'value' => ArrayHelper::getValue($dataProvider['filter'], 'section_id'),
                    'data' => $belongSection,
                    'hideSearch' => false,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'filterOptions' => [
                    'class' => 'hidden-xs hidden-sm',
                ],
                'headerOptions' => [
                    'class' => [
                        'th' => 'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'padding' => '8px'
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['section_name']) ? $data['section_name'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td hidden-xs hidden-sm',
                ],
            ],
            [
                'attribute' => 'activity_name',
                'label' => Yii::t(null, '{Belong}{Activity}', [
                    'Belong' => Yii::t('app', 'Belong'),
                    'Activity' => Yii::t('app', 'Activity')
                ]),
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'name' => 'activity_id',
                    'value' => ArrayHelper::getValue($dataProvider['filter'], 'activity_id'),
                    'data' => $belongActivity,
                    'hideSearch' => false,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'headerOptions' => [
                    'style' => [
                        'padding' => '8px'
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['activity_name']) ? $data['activity_name'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td',
                ],
            ],
            [
                'attribute' => 'file_id',
                'label' => Yii::t(null, '{File}{Name}', [
                    'File' => Yii::t('app', 'File'),
                    'Name' => Yii::t('app', 'Name')
                ]),
                'format' => 'raw',
                'headerOptions' => [
                    'style' => [
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
                    'name' => 'created_by',
                    'value' => ArrayHelper::getValue($dataProvider['filter'], 'created_by'),
                    'data' => $uploadBy,
                    'hideSearch' => true,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'filterOptions' => [
                    'class' => 'hidden-xs hidden-sm',
                ],
                'headerOptions' => [
                    'class' => [
                        'th' => 'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '95px',
                        'padding' => '8px'
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['created_by']) ? $data['created_by'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td hidden-xs hidden-sm',
                ],
            ],
            [
                'attribute' => 'created_at',
                'label' => Yii::t(null, '{Upload}{Time}', [
                    'Upload' => Yii::t('app', 'Upload'),
                    'Time' => Yii::t('app', 'Time')
                ]),
                'format' => 'raw',
                'filter' => '',
                'headerOptions' => [
                    'class' => [
                        'th' => 'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '100px',
                        'padding' => '8px'
                    ],
                ],
                'value' => function($data) {
                    return !empty(date('Y-m-d H:i', $data['created_at'])) ? date('Y-m-d H:i', $data['created_at']) : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td hidden-xs hidden-sm',
                ],
            ],
            [
                'attribute' => 'expire_time',
                'label' => Yii::t(null, '{Expire}{Time}', [
                    'Expire' => Yii::t('app', 'Expire'),
                    'Time' => Yii::t('app', 'Time')
                ]),
                'format' => 'raw',
                'filter' => '',
                'headerOptions' => [
                    'class' => [
                        'th' => 'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '100px',
                        'padding' => '8px'
                    ],
                ],
                'value' => function($data) {
                    return !empty(date('Y-m-d H:i', $data['expire_time'])) ? date('Y-m-d H:i', $data['expire_time']) : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td hidden-xs hidden-sm',
                ],
            ],
            [
                'header' => Yii::t('app', 'Operating'),
                'headerOptions' => [
                    'style' => [
                        'width' => '74px',
                    ],
                ],
                'format' => 'raw',
                'value' => function($data) {
                    return Html::a('<span class="fa fa-download">下载</span>', ['/webuploader/default/download', 'file_id' => $data['file_id']], [
                                'class' => 'btn btn-success btn-sm', 'target' => '_blank',
                    ]);
                },
                'contentOptions' => [
                    'style' => [
                        'width' => '74px',
                    ],
                ],
            ],
        ],
    ]);?>
</div>
<?php
    McbsAssets::register($this);
?>