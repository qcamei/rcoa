<?php

use common\models\mconline\McbsCourse;
use common\models\mconline\searchs\McbsActivityFileSearch;
use common\modules\preview\controllers\DefaultController;
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
        <h4>文件列表</h4>
        <?php // Html::a(Yii::t('app', 'Back'), ['default/view' . '?id=' . $couModel->id], ['class' => 'btn btn-default']) ?>
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
                    'style' => [
                        'padding' => '8px 3px',
                    ],
                ],
                'headerOptions' => [
                    'class' => [
                        'th' => 'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'padding' => '8px 3px',
                        'width' => '130px'
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['chapter_name']) ? $data['chapter_name'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td hidden-xs hidden-sm',
                    'style' => [
                        'padding' => '8px 3px',
                    ],
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
                    'style' => [
                        'padding' => '8px 3px',
                    ],
                ],
                'headerOptions' => [
                    'class' => [
                        'th' => 'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'padding' => '8px 3px',
                        'width' => '130px'
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['section_name']) ? $data['section_name'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td hidden-xs hidden-sm',
                    'style' => [
                        'padding' => '8px 3px',
                    ],
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
                'filterOptions' => [
                    'style' => [
                        'padding' => '8px 3px',
                    ],
                ],
                'headerOptions' => [
                    'style' => [
                        'padding' => '8px 3px',
                        'width' => '220px'
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['activity_name']) ? $data['activity_name'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td',
                    'style' => [
                        'padding' => '8px 3px',
                    ],
                ],
            ],
            [
                'attribute' => 'filename',
                'label' => Yii::t(null, '{File}{Name}', [
                    'File' => Yii::t('app', 'File'),
                    'Name' => Yii::t('app', 'Name')
                ]),
                'format' => 'raw',
                'filterOptions' => [
                    'style' => [
                        'padding' => '8px 3px',
                    ],
                ],
                'headerOptions' => [
                    'style' => [
                        'padding' => '8px 3px',
                        'width' => '260px'
                    ],
                ],
                'value' => function($data) {
                    return $data['is_del'] ? "<span style=\"color:#ccc\">{$data['filename']}</span>" : 
                                ($data['status']? 
                                    $data['filename'].Html::img(WEB_ROOT.'/filedata/image/new.gif',['style'=>'margin-top:-20px']):$data['filename']);
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td',
                    'style' => [
                        'padding' => '8px 3px',
                    ],
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
                    'style' => [
                        'padding' => '8px 3px',
                    ],
                ],
                'headerOptions' => [
                    'class' => [
                        'th' => 'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '85px',
                        'padding' => '8px 3px',
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['nickname']) ? $data['nickname'] : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td hidden-xs hidden-sm',
                    'style' => [
                        'padding' => '8px 3px',
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
                'filter' => FALSE,
                'headerOptions' => [
                    'class' => [
                        'th' => 'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '85px',
                        'padding' => '8px 3px',
                    ],
                ],
                'value' => function($data) {
                    return !empty(date('Y-m-d H:i', $data['created_at'])) ? date('Y-m-d H:i', $data['created_at']) : NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td hidden-xs hidden-sm',
                    'style' => [
                        'padding' => '8px 3px',
                    ],
                ],
            ],
            [
                'attribute' => 'expire_time',
                'label' => Yii::t(null, '{Expire}{Time}', [
                    'Expire' => Yii::t('app', 'Expire'),
                    'Time' => Yii::t('app', 'Time')
                ]),
                'format' => 'raw',
                'filter' => FALSE,
                'headerOptions' => [
                    'class' => [
                        'th' => 'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '85px',
                        'padding' => '8px 3px',
                    ],
                ],
                'value' => function($data) {
                    if($data['expire_time'] != null){
                        if((($data['expire_time'] - time()) / 86400) <= 7){
                            return '<span style="color:red;">'.date('Y-m-d H:i', $data['expire_time']).'</span>';
                        }
                        return date('Y-m-d H:i', $data['expire_time']);
                    }
                    return NULL;
                },
                'contentOptions' => [
                    'class' => 'activity-name list-td hidden-xs hidden-sm',
                    'style' => [
                        'padding' => '8px 3px',
                    ],
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Operating'),
                'buttons' => [
                    'view' => function ($url, $data, $key) {
                        $file_id = $data['file_id'];
                        $fileType = DefaultController::checkupLook($file_id);
                        $options = [
                            'class' => 'btn btn-sm ' . (($fileType == '10') ? ($data['is_del'] ? 'btn-danger disabled' : 'btn-danger') : 'btn-info'),
                            'title' => ($fileType == '10') ? '该文件暂不支持预览' : Yii::t('app', 'Preview'),
                            'aria-label' => ($fileType == '10') ? '该文件暂不支持预览' : Yii::t('app', 'Preview'),
                            'target' => '_blank',
                            'data-pjax' => '0',
                        ];
                        $buttonHtml = [
                            'name' => !$data['is_del'] ? '<span class="fa fa-eye"></span> '.Yii::t('app', 'Preview') : '已删除',
                            'url' => ['/preview/default/index', 'file_id'=>$data['file_id']],
                            'options' => $options,
                            'symbol' => '&nbsp;',
                            'conditions' => true,
                            'adminOptions' => true,
                        ];
                        return Html::a($buttonHtml['name'],$buttonHtml['url'],$buttonHtml['options']);
                        //return ResourceHelper::a($buttonHtml['name'], $buttonHtml['url'],$buttonHtml['options'],$buttonHtml['conditions']);
                    }
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '65px',
                        'padding' => '8px 3px',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'padding' => '8px 3px',
                    ],
                ],
                'template' => '{view}',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => Yii::t('app', 'Operating'),
                'buttons' => [
                    'view' => function ($url, $data, $key) {
                        $options = [
                            'class' => 'btn btn-sm '.($data['is_del'] ? 'btn-danger disabled' : 'btn-success'),
                            'title' => Yii::t('app', 'Download'),
                            'aria-label' => Yii::t('app', 'Download'),
                            'data-pjax' => '0',
                        ];
                        $buttonHtml = [
                            'name' => !$data['is_del'] ? '<span class="fa fa-download"></span> '.Yii::t('app', 'Download') : '已删除',
                            'url' => ['course-make/download', 'activity_id'=>$data['activity_id'],'file_id'=>$data['file_id']],
                            'options' => $options,
                            'symbol' => '&nbsp;',
                            'conditions' => true,
                            'adminOptions' => true,
                        ];
                        return Html::a($buttonHtml['name'],$buttonHtml['url'],$buttonHtml['options']);
                        //return ResourceHelper::a($buttonHtml['name'], $buttonHtml['url'],$buttonHtml['options'],$buttonHtml['conditions']);
                    }
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '65px',
                        'padding' => '8px 3px',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'padding' => '8px 3px',
                    ],
                ],
                'template' => '{view}',
            ],
        ],
    ]);?>
</div>
<?php
    McbsAssets::register($this);
?>