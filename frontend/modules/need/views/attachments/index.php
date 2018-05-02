<?php

use common\models\need\NeedAttachments;
use common\models\need\searchs\NeedAttachmentsSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel NeedAttachmentsSearch */
/* @var $dataProvider ActiveDataProvider */

//$this->title = Yii::t('app', 'Need Attachments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="need-attachments-index">
    <div class="col-xs-12 frame">
    
        <div class="col-xs-12 title">
            <i class="fa fa-paperclip"></i>
            <span><?= Yii::t('app', '需求附件') ?></span>
        </div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'summaryOptions' => ['class' => 'hidden'],
            'pager' => [
                'options' => ['class' => 'hidden']
            ],
            'tableOptions' => [
                'class' => 'table table-striped table-list',
                'style' => 'border: 1px #ddd solid;'
            ],
            'columns' => [
                [
                    'label' => '文件名',
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model NeedAttachments */
                        return !empty($model->upload_file_id) ? $model->uploadfile->name : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th' => 'hidden',
                        ],
                        'style' => [
                            'width' => '900px',
                            'padding' => '8px 4px',
                        ],
                    ],
                    'contentOptions' =>[
                        'class' => [
                            //'td' => 'hidden-xs'
                        ],
                        'style' => [
                            'padding' => '8px 4px',
                            'white-space' => 'nowrap',
                        ],
                    ],
                ],
                [
                    'label' => '大小',
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model NeedAttachments */
                        return !empty($model->upload_file_id) ? Yii::$app->formatter->asShortSize($model->uploadfile->size) : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th' => 'hidden',
                        ],
                        'style' => [
                            'width' => '100px',
                            'padding' => '8px 4px',
                        ],
                    ],
                    'contentOptions' =>[
                        'class' => [
                            //'td' => 'hidden-xs'
                        ],
                        'style' => [
                            'padding' => '8px 4px',
                            'white-space' => 'nowrap',
                        ],
                    ],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            /* @var $model NeedAttachments */
                            $options = [
                                //'class' => 'btn btn-sm btn-info',
                                'title' =>  Yii::t('app', 'Preview'),
                                'aria-label' => Yii::t('app', 'Preview'),
                                'target' => '_blank',
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="fa fa-eye"></span>', ['/preview/default/index', 'file_id' => $model->upload_file_id], $options). ' ';
                        },
                        'download' => function ($url, $model) {
                            /* @var $model NeedAttachments */
                            $options = [
                                //'class' => 'btn btn-sm btn-info',
                                'title' =>  Yii::t('app', 'Download'),
                                'aria-label' => Yii::t('app', 'Download'),
                                'data-pjax' => '0',
                            ];
                            return Html::a('<span class="fa fa-download"></span>', ['/webuploader/default/download', 'file_id' => $model->upload_file_id], $options);
                        }
                    ],
                    'headerOptions' => [
                        'class' => [
                            //'th' => 'hidden'
                        ],
                        'style' => [
                            'width' => '50px',
                            'padding' => '8px 3px',
                        ],
                    ],
                    'contentOptions' =>[
                        'style' => [
                            'width' => '50px',
                            'padding' => '8px 3px',
                        ],
                    ],
                    'template' => '{view}{download}',
                ],
            ],
        ]); ?>
    
    </div>    
</div>
