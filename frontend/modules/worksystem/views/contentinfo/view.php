<?php

use common\models\worksystem\WorksystemContent;
use common\models\worksystem\WorksystemContentinfo;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model WorksystemContentinfo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Contentinfos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container worksystem worksystem-contentinfo-view">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($model->worksystemContent->type_name.'-'.WorksystemContent::$modeName[$model->is_new]) ?></h4>
            </div>
            <div class="modal-body">
                    
               <?= DetailView::widget([
                    'model' => $model,
                    'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
                    'attributes' => [
                        [
                            'attribute' => 'worksystem_content_id',
                            'format' => 'raw',
                            'value' => !empty($model->worksystem_content_id) ? $model->worksystemContent->type_name : null,
                        ],
                        [
                            'attribute' => 'is_new',
                            'format' => 'raw',
                            'value' => WorksystemContent::$modeName[$model->is_new],
                        ],
                        [
                            'attribute' => 'price',
                            'format' => 'raw',
                            'value' => '￥'. number_format($model->price, 2, '.', ','),
                        ],
                        [
                            'attribute' => 'budget_number',
                            'format' => 'raw',
                            'value' => $model->budget_number.(!empty($model->worksystem_content_id) ? $model->worksystemContent->unit : ''),
                        ],
                        [
                            'attribute' => 'budget_cost',
                            'format' => 'raw',
                            'value' => '￥'. number_format($model->budget_cost, 2, '.', ','),
                        ],
                        [
                            'attribute' => 'reality_number',
                            'format' => 'raw',
                            'value' => $model->reality_number == 0 ? null : $model->reality_number.(!empty($model->worksystem_content_id) ? $model->worksystemContent->unit : ''),
                        ],
                        [
                            'attribute' => 'reality_cost',
                            'format' => 'raw',
                            'value' => $model->reality_number == 0 ?  null : '￥'. number_format($model->reality_number, 2, '.', ','),
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => 'raw',
                            'value' => date('Y-m-d H:i', $model->created_at),
                        ],
                        [
                            'attribute' => 'updated_at',
                            'format' => 'raw',
                            'value' => date('Y-m-d H:i', $model->updated_at),
                        ],
                    ],
                ]) ?>
                
            </div>
            <div class="modal-footer">
                <button id="submit-save" class="btn btn-default" data-dismiss="modal" aria-label="Close">关闭</button>
            </div>
       </div>
    </div>
    
</div>

<?php
$js =   
<<<JS
     
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>