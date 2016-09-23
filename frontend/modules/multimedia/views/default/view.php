<?php

use common\models\multimedia\MultimediaCheck;
use common\models\multimedia\MultimediaTask;
use frontend\modules\multimedia\MultimediaAsset;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model MultimediaTask */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="title">
    <div class="container">
        <?= $this->title ?>
    </div>
</div>

<div class="container multimedia-manage-view has-title multimedia-task">
    <?= $this->render('_form_detai', [
        'model' => $model,
        'multimedia' => $multimedia,
        'workload' => $workload,
        'producerList' => $producerList,
        'producer' => $producer
    ]) ?>
    
    <?= $this->render('_form_brace_model',[
        'model' => $model,
        'teams' => $teams,
    ])?>
    
    <?= $this->render('_form_complete_model',[
        'model' => $model,
    ])?>
    
    <h4>审核记录</h4>
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->multimediaChecks,
        ]),
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                'label' => Yii::t('rcoa/multimedia', 'Title'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model MultimediaCheck */
                    return $model->title;
                },
                'headerOptions' => [
                    'style' => [
                        'width' => '134px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'course-name',
                    'style' => [
                        //'width' => '114px', 
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa', 'Remark'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model MultimediaCheck */
                    return $model->remark;
                },
                'headerOptions' => [
                    'style' => [
                        'max-width' => '154px',
                        //'min-width' => '84px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'course-name',
                    'style' => [
                        'max-width' => '154px',
                        //'min-width' => '84px',
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Created At'), 
                'value'=> function($model){
                    /* @var $model MultimediaCheck */
                    return date('Y-m-d H:i', $model->created_at);
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '114px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'hidden-xs',
                    'style' => [
                        'width' => '114px',
                        'font-size' => '10px;',
                        'color' => '#ccc',
                        'padding' => '4px 8px'
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Updated At'),
                'value'=> function($model){
                    /* @var $model MultimediaCheck */
                    return date('Y-m-d H:i', $model->updated_at);
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '114px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'hidden-xs',
                    'style' => [
                        'width' => '114px',
                        'font-size' => '10px;',
                        'color' => '#ccc',
                        'padding' => '4px 8px'
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Complete Time'),
                'value'=> function($model){
                    /* @var $model MultimediaCheck */
                    return $model->carry_out_time;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '114px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'hidden-xs',
                    'style' => [
                        'width' => '114px',
                        'font-size' => '10px;',
                        'color' => '#ccc',
                        'padding' => '4px 8px'
                    ],
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('rcoa', 'Operating'),
                'buttons' => [
                    'view' => function ($url, $model) {
                        /* @var $model MultimediaCheck */
                        $options = [
                            'class' => 'btn btn-default',
                        ];
                        $icon = $model->status == MultimediaCheck::STATUS_COMPLETE ? 'icon task-complete' : 'icon working';
                        return Html::a('<i class="'.$icon.'"></i>'.Yii::t('rcoa', 'View'), 
                            ['check/view', 'id' => $model->id], $options);
                    },
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '84px'  
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'width' => '84px',
                        'padding' =>'4px',
                    ],
                ],
                'template' => '{view}',
            ],
        ],
    ]); ?>
    
</div>

<?= $this->render('_form_view',[
    'model' => $model,
    'multimedia' => $multimedia,
])?>


<?php
$js = 
<<<JS
    /** 指派操作 */
    $('#submit').click(function()
    {
        $('#form-assign').submit();
    });
        
    /** 支撑操作 close关闭模态款的 */
    $("#braceModal .modal-header .close").click(function()
    {
        window.location.reload();
    });
    /** 支撑操作 关闭模态框后重新加载页面 */
    $("#braceModal .modal-footer #brace-close").click(function()
    {
        window.location.reload();
    });
        
    /** 寻求支撑 弹出模态框*/
    $('#seek-brace').click(function()
    {
        $('#braceModal').modal();
    });
        
    /** 支撑操作 提交表单 */
    $("#braceModal .modal-footer #brace-save").click(function()
    {
        $('#form-seek-brace').submit();
    });
        
    /** 完成操作 close关闭模态款的 */
    $("#completeModal .modal-header .close").click(function()
    {
        window.location.reload();
    });
    /** 完成操作 关闭模态框后重新加载页面 */
    $("#completeModal .modal-footer #complete-close").click(function()
    {
        window.location.reload();
    });
        
    /** 完成操作 弹出模态框 */
    $('#complete').click(function()
    {
        $('#completeModal').modal();
    });
        
    /** 完成操作 提交表单 */
    $("#completeModal .modal-footer #complete-save").click(function()
    {
        $('#form-complete').submit();
    });
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    MultimediaAsset::register($this);
?>