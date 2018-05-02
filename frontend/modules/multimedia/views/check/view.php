<?php

use common\models\multimedia\MultimediaCheck;
use frontend\modules\multimedia\MultimediaAsset;
use wskeee\rbac\RbacName;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model MultimediaCheck */

$this->title = Yii::t('rcoa/multimedia', 'Multimedia Check View').':'.$model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Checks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel"><?= $this->title ?></h4>
</div>
<div class="modal-body">
    
    <?= DetailView::widget([
        'model' => $model,
        'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
        'attributes' => [
            [
                'attribute' => 'task_id',
                'value' => !empty($model->task_id) ? $model->task->name : '',
            ],
            [
                'attribute' => 'title',
                'value' => $model->title,
            ],
            [
                'attribute' => 'create_by',
                'value' => !empty($model->create_by) ? $model->createBy->nickname : '',
            ],
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d H:i', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('Y-m-d H:i', $model->updated_at),
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Complete Time'),
                'value' => $model->real_carry_out,
            ],
            [
                'attribute' => 'remark',
                'format' => 'raw',
                'value' => '<div style="height:65px; vertical-align:middle; display:table-cell">'.$model->remark.'</div>',
            ],
        ],
    ]) ?>
    
</div>
<div class="modal-footer">
    <?php
        /**
         * 编辑 按钮显示必须满足以下条件：
         * 1、拥有编辑的权限
         * 2、创建者是自己
         * 3、审核状态必须是【未完成】
         */
        if(Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_UPDATE_CHECK) 
          && $model->create_by == Yii::$app->user->id && $model->status == MultimediaCheck::STATUS_NOTCOMPLETE)
            echo Html::a('编辑', ['update', 'id' => $model->id], ['id' => 'check-update', 'class' => 'btn btn-primary']).' ';
        /**
         * 编辑 按钮显示必须满足以下条件：
         * 1、拥有删除的权限
         * 2、创建者是自己
         * 3、审核状态必须是【未完成】
         */
        if(Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_DELETE_CHECK) 
           && $model->create_by == Yii::$app->user->id && $model->status == MultimediaCheck::STATUS_NOTCOMPLETE)
            echo Html::a('删除', ['delete', 'id' => $model->id], ['data' => ['method' => 'post'], 'class' => 'btn btn-danger']);
    ?>
</div>


<div id="updateModal" class="fade modal myModal" role="dialog" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>

<?php
$js = 
<<<JS
    /*$('#submit').click(function()
    {
        $('#multimedia-check-form').submit();
    });*/
        
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    MultimediaAsset::register($this);
?>