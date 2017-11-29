<?php

use common\models\ScheduledTaskLog;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model ScheduledTaskLog */

$this->title = Yii::t('null', '【{File}{Expire}{Check}】', [
            'File' => Yii::t('app', 'File'),
            'Expire' => Yii::t('app', 'Expire'),
            'Check' => Yii::t('app', 'Check'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('null', '{Daily}{Task}{Log}{Administration}', [
        'Daily' => Yii::t('app', 'Daily'),
        'Task' => Yii::t('app', 'Task'),
        'Log' => Yii::t('app', 'Log'),
        'Administration' => Yii::t('app', 'Administration'),
    ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
if($result == 1){
    $detailAttributes = [
        [
            'label' => '文件总数',
            'format' => 'raw',
            'value' => '<span style="color:green">' . $model['success_num']. '成功' . '</span>' .
            ' / ' . '<span style="color:red">' . $model['fail_num'] . '失败' . '</span>' ,
        ],
        [
            'label' => '删除总数',
            'value' => $model['all_size'] . ' GB',
        ],
        [
            'label' => '标记结果',
            'format' => 'raw',
            'value' => $model['mark_del_result'] ? '<span class="fa fa-check" style="color:green">成功</span>' :
                        '<span class="fa fa-times" style="color:red">失败</span>',
        ],
        [
            'label' => '标记反馈',
            'format' => 'raw',
            'value' => $model['mark_del_result'] ? '无' : '<span style="color:red">' . $model['mark_del_mes'] . '</span',
        ],
    ];
}else{
    $detailAttributes = [
        [
            'label' => '结果',
            'format' => 'raw',
            'value' => $result ? '<span class="fa fa-check" style="color:green">成功</span>' :
                        '<span class="fa fa-times" style="color:red">失败</span>',
        ],
        [
            'label' => '备注',
            'format' => 'raw',
            'value' => '<span style="color:red">' . $model . '</span>' ,
        ],
    ];
}
?>
<div class="mconline_admin-default-index mcbs default-view">
    <p>
        <span style="font-size: 16px; color: #999">日志详情 </span>
        <?= Html::a(Yii::t('app', 'Back'), Yii::$app->request->getReferrer(), ['class' => 'btn btn-default'])?>
    </p>
    <div class="col-md-12 col-xs-12 frame frame-left">
        <p><h4>基本信息：</h4></p>
        <?= DetailView::widget([
            'model' => $model,
            'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
            'attributes' => $detailAttributes,
        ])?>
        
        <?php 
        if($result == 1){
            echo '<p><h4>处理详情：</h4></p>';
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'label' => Yii::t(null, '{File}{ID}',[
                            'File' => Yii::t('app', 'File'),
                            'ID' => Yii::t('app', 'ID'),
                        ]),
                        'value'=> function($data){
                            return $data['file_id'];
                        },
                        'headerOptions' => [
                            'style' => [
                                'min-width'=> '150px',
                                'padding' => '8px',
                                'text-align' => 'center'
                            ],
                        ],
                        'contentOptions' => [
                            'style' => [
                                'text-align' => 'center',
                                'word-break'=> 'break-word'
                            ]
                        ],
                    ],
                    [
                        'label' => Yii::t(null, '{File}{Name}',[
                            'File' => Yii::t('app', 'File'),
                            'Name' => Yii::t('app', 'Name'),
                        ]),
                        'value'=> function($data){
                            return $data['file_name'];
                        },
                        'headerOptions' => [
                            'style' => [
                                'min-width'=> '100px',
                                'padding' => '8px',
                                'text-align' => 'center'
                            ],
                        ],
                        'contentOptions' => [
                            'style' => [
                                'text-align' => 'center',
                                'word-break'=> 'break-word'
                            ]
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Route'),
                        'value'=> function($data){
                            return $data['file_path'];
                        },
                        'headerOptions' => [
                            'style' => [
                                'min-width'=> '280px',
                                'padding' => '8px',
                                'text-align' => 'center'
                            ],
                        ],
                        'contentOptions' => [
                            'style' => [
                                'text-align' => 'center',
                                'word-break'=> 'break-word'
                            ]
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Size'),
                        'value'=> function($data){
                            return (round($data['file_size'] / (1024 * 1024), 2) . 'MB');
                        },
                        'headerOptions' => [
                            'style' => [
                                'padding' => '8px',
                                'text-align' => 'center'
                            ],
                        ],       
                    ],
                    [
                        'label' => Yii::t('app', 'Result'),
                        'format' => 'raw',
                        'value'=> function($data){
                            return $data['result'] ? '<span class="fa fa-check" style="color:green">成功</span>' :
                                    '<span class="fa fa-times" style="color:red">失败</span>';
                        },
                        'headerOptions' => [
                            'style' => [
                                'min-width'=> '60px',
                                'padding' => '8px',
                                'text-align' => 'center'
                            ],
                        ],
                        'contentOptions' => [
                            'style' => [
                                'padding' => '8px',
                                'text-align' => 'center'
                            ],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Reason'),
                        'value'=> function($data){
                            return $data['mes'];
                        },
                        'headerOptions' => [
                            'style' => [
                                'padding' => '8px',
                                'text-align' => 'center'
                            ],
                        ],
                    ],
                ]
            ]);
        } else {
            echo '';
        }
        ?>
    </div>
</div>
<?php
    McbsAssets::register($this);

