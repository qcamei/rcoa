<?php

use common\models\teamwork\ItemManage;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model ItemManage */

?>
<div class="item-manage-view">
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'item_type_id',
                'value' => $model->itemType->name,
            ],
            [
                'attribute' => 'item_id',
                'value' => $model->item->name,
            ],
            [
                'attribute' => 'item_child_id',
                'value' => $model->itemChild->name,
            ],
            [
                'attribute' => 'create_by',
                'value' => $model->teamMember->team->name.' ( '.$model->createBy->nickname.' )',
            ],
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d H:i', $model->created_at),
            ],
            [
                'attribute' => 'forecast_time',
                'value' => $model->forecast_time,
            ],
            [
                'attribute' => 'real_carry_out',
                'value' => $model->real_carry_out,
            ],
            [
                'label' => '当前进度',
                'format' => 'raw',
                'value' => '0%',
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->getIsTimeOut() ? 
                        '<span style="color:red">'.$model->statusName[$model->status].'</span>' : 
                        $model->statusName[$model->status],
            ],
            [
                'attribute' => 'background',
                'format' => 'raw',
                'value' => '<div style="height:65px;">'.$model->background.'</div>',
            ],
            [
                'attribute' => 'use',
                'format' => 'raw',
                'value' => '<div style="height:65px;">'.$model->use.'</div>',
            ],
        ],
    ]) ?>

</div>
