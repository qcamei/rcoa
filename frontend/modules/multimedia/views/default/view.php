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

$this->title = Yii::t('rcoa/multimedia', 'Multimedia Task View').' : '.$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="title">
    <div class="container course-name">
        <?= $this->title ?>
    </div>
</div>

<div class="container multimedia-manage-view has-title multimedia-task">
    <?= $this->render('_form_detai', [
        'model' => $model,
        'multimedia' => $multimedia,
        'workload' => $workload,
        'producer' => $producer
    ]) ?>
    
    <?= $this->render('_form_brace_model',[
        'model' => $model,
        'teams' => $teams,
    ])?>
    
    <?= $this->render('_form_complete_model',[
        'model' => $model,
    ])?>
    
    <?= $this->render('_form_cancel_model', [
        'model' => $model,
    ])?>
    
    <?= $this->render('_form_assign_model', [
        'model' => $model,
        'producerList' => $producerList,
    ])?>
    
    <?= $this->render('/check/_form_model')?>
    
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
                        'width' => '95px',
                        'padding' => '8px;'
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'course-name',
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
                        'min-width' => '134px',
                        'padding' => '8px;'
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'course-name',
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
                        'width' => '117px',
                        'padding' => '8px;'
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'hidden-xs',
                    'style' => [
                        'font-size' => '10px;',
                        'color' => '#777',
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
                        'width' => '117px',
                        'padding' => '8px;'
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'hidden-xs',
                    'style' => [
                        'font-size' => '10px;',
                        'color' => '#777',
                        'padding' => '4px 8px'
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Complete Time'),
                'value'=> function($model){
                    /* @var $model MultimediaCheck */
                    return empty($model->real_carry_out) ? '' : $model->real_carry_out;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '117px',
                        'padding' => '8px;'
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'hidden-xs',
                    'style' => [
                        'font-size' => '10px;',
                        'color' => '#777',
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
                            'class' => 'btn btn-default view-check',
                        ];
                        $icon = $model->status == MultimediaCheck::STATUS_COMPLETE ? 'icon task-complete' : 'icon working';
                        return Html::a('<i class="'.$icon.'"></i>'.Yii::t('rcoa', 'View'), 
                            ['check/view', 'id' => $model->id], $options);
                    },
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '84px',
                        'padding' => '8px;'
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
    /** 添加审核操作 弹出模态框 */
    $('#check-create').click(function()
    {
        var urlf = $(this).attr("href");
        $('#myModal').modal({remote:urlf});
        return false;
    });
    
    /** 指派操作 弹出模态框 */
    $('#assign').click(function()
    {
        $('#assignModal').modal();
    });
    /** 指派操作 提交表单 */
    $("#assignModal .modal-footer #assign-save").click(function()
    {
        $('#form-assign').submit();            
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
    
    /** 取消操作 弹出模态框 */
    $('#cancel').click(function()
    {
        $('#cancelModal').modal();
    });
    /** 取消操作 提交表单 */
    $("#cancelModal .modal-footer #cancel-save").click(function()
    {
        $('#form-cancel').submit();
    });
    
    /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。 */
    $('#myModal').on('hidden.bs.modal', function(){
        window.location.reload();
    });
    /** 查看审核 */   
    $('.view-check').click(function(){
        var urlf = $(this).attr("href");
        $("#myModal").modal({remote:urlf});
        return false;
    });
    /** 审核操作 从远端的数据源加载完数据之后触发该事件*/
    $('#myModal').on('loaded.bs.modal', function ()
    {
        
        /** 编辑审核操作 */
        $('#check-update').click(function()
        {
            var urlf = $(this).attr("href");
            var a = $('#updateModal').modal({remote:urlf});
            return false;
        });
        /** 审核操作 提交表单 */
        $("#myModal .modal-footer #create-check-save").click(function()
        {
            $('#multimedia-check-form').submit();            
        });
        /** 审核操作 提交表单 */
        $("#updateModal .modal-footer #update-check-save").click(function()
        {
            $('#multimedia-check-form').submit();            
        });
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    MultimediaAsset::register($this);
?>