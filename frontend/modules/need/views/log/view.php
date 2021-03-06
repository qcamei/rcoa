<?php

use common\models\need\NeedTaskLog;
use frontend\modules\need\assets\ModuleAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model NeedTaskLog */

ModuleAssets::register($this);

$this->title = $model->title;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="need-task-log-view">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="modal-body">
                <?= DetailView::widget([
                    'model' => $model,
                    //'options' => ['class' => 'table table-bordered detail-view '],
                    'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
                    'attributes' => [
                        'id',
                        'action',
                        'title',
                        [
                            'attribute' => 'content',
                            'format' => 'raw',
                            'value' => implode("<br/>",explode("\n\r", $model->content)),
                        ],
                        [
                            'attribute' => 'created_by',
                            'value' => !empty($model->created_by) ? $model->createdBy->nickname : null,
                        ],
                        [
                            'attribute' => 'created_at',
                            'value' => date('Y-m-d H:i',$model->created_at),
                        ],
                        [
                            'attribute' => 'updated_at',
                            'value' => date('Y-m-d H:i',$model->updated_at),
                        ],
                    ],
                ]) ?>
            </div>
            <div class="modal-footer">
                <?= Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default','data-dismiss' => 'modal', 'aria-label' => 'Close']) ?>
            </div>
       </div>
    </div>

</div>

<?php
$js = 
<<<JS
        
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>