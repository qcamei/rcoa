<?php

use common\models\demand\DemandAcceptance;
use common\models\demand\DemandCheck;
use frontend\modules\demand\assets\DemandAssets;
use wskeee\rbac\RbacName;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model DemandAcceptance */

$this->title = Yii::t('rcoa/demand', 'Demand Acceptance View').':'.$model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Acceptances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
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
                        'value' => !empty($model->task_id) ? $model->task->course->name : null,
                    ],
                    [
                        'attribute' => 'title',
                        'value' => $model->title,
                    ],
                    [
                        'attribute' => 'create_by',
                        'value' => !empty($model->create_by) ? $model->createBy->nickname : null,
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
                        'value' => $model->complete_time,
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
                 * 3、验收状态必须是【未完成】
                 */
                if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_UPDATE_ACCEPTANCE) 
                  && $model->create_by == Yii::$app->user->id && $model->status == DemandAcceptance::STATUS_NOTCOMPLETE)
                    echo Html::a('编辑', ['update', 'id' => $model->id], ['id' => 'acceptance-update', 'class' => 'btn btn-primary']).' ';
            ?>

        </div>
   </div>
</div>  

<script type="text/javascript">
    /** 编辑验收操作 */
    $('#acceptance-update').click(function()
    {
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
</script>

<?php
    DemandAssets::register($this);
?>
