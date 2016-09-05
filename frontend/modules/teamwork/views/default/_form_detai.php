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
                'value' => !empty($model->item_type_id) ? $model->itemType->name : '空',
            ],
            [
                'attribute' => 'item_id',
                'value' => !empty($model->item_id) ? $model->item->name : '空',
            ],
            [
                'attribute' => 'item_child_id',
                'value' => !empty($model->item_child_id) ? $model->itemChild->name : '空',
            ],
           
            ['label' => '<span class="btn-block viewdetail-th-head">开发信息</span>','value'=>''],
            [
                'attribute' => 'forecast_time',
                'value' => $model->forecast_time,
            ],
            
            [
                'label' => Yii::t('rcoa/teamwork', 'Now Progress'),
                'format' => 'raw',
                'value' => (int)($model->progress * 100).'%',
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
