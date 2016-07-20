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
       //'options' => ['class' => 'table table-bordered detail-view'],
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            ['label' => '<span class="btn-block viewdetail-th-head">基本信息</span>','value' => ''],
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
            /*[
                'attribute' => 'team_id',
                'value' => $model->team->name,
            ],*/
            ['label' => '<span class="btn-block viewdetail-th-head">开发信息</span>','value'=>''],
            [
                'attribute' => 'forecast_time',
                'value' => $model->forecast_time,
            ],
            /*[
                'attribute' => 'real_carry_out',
                'value' => $model->real_carry_out,
            ],*/
            [
                'label' => Yii::t('rcoa/teamwork', 'Now Progress'),
                'format' => 'raw',
                'value' => (int)($model->progress * 100).'%',
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->getIsTimeOut() ? 
                        '<span style="color:red">'.$model->statusName[$model->status].'</span>' : 
                        $model->statusName[$model->status],
            ],
            ['label' => '<span class="btn-block viewdetail-th-head">其它信息</span>','value'=>''],
            [
                'attribute' => 'create_by',
                //'value' => $model->teamMember->team->name.' ( '.$model->createBy->nickname.' )',
                'value' => $model->createBy->nickname,
            ],
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d H:i', $model->created_at),
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
