<?php

use common\models\ScheduledTaskLog;
use mconline\modules\mcbs\assets\McbsAssets;
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

if($model['result'] == 1){
    $attributes = [
        [
            'label' => '结果',
            'format' => 'raw',
            'value' => $model['result'] ? '<span class="fa fa-check" style="color:green">成功</span>' :
                        '<span class="fa fa-times" style="color:red">失败</span>',
        ],
        [
            'label' => '实际占用',
            'format' => 'raw',
            'value' => $model['feedback']['current_value'],
        ],
        [
            'label' => '警戒设置',
            'format' => 'raw',
            'value' => $model['feedback']['warning_value'],
        ],
        [
            'label' => '上限设置',
            'format' => 'raw',
            'value' => $model['feedback']['max_value'],
        ],
        [
            'label' => '剩余空间',
            'format' => 'raw',
            'value' => $model['feedback']['remain_value'],
        ],
        [
            'label' => '备注',
            'format' => 'raw',
            'value' => $model['feedback']['current_value'] > $model['feedback']['warning_value'] ? 
                '<span style="color:red">' . $model['feedback']['des'] . '</span>' : $model['feedback']['des'],
        ],
    ];
}else{
    $attributes = [
        [
            'label' => '结果',
            'format' => 'raw',
            'value' => $model['result'] ? '<span class="fa fa-check" style="color:green">成功</span>' :
                        '<span class="fa fa-times" style="color:red">失败</span>',
        ],
        [
            'label' => '备注',
            'format' => 'raw',
            'value' => '<span style="color:red">' . $model['feedback'] . '</span>' ,
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
        <?= DetailView::widget([
            'model' => $model,
            'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
            'attributes' => $attributes,
        ])?>
    </div>
</div>
<?php
    McbsAssets::register($this);

