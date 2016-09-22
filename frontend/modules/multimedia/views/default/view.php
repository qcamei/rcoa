<?php

use common\models\multimedia\MultimediaCheck;
use common\models\multimedia\MultimediaTask;
use common\models\teamwork\CourseManage;
use frontend\modules\multimedia\MultimediaAsset;
use frontend\modules\multimedia\MultimediaTool;
use wskeee\rbac\RbacName;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model MultimediaTask */
/* @var $multimedia MultimediaTool */

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
                        return Html::a(Yii::t('rcoa', 'View'), 
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

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), '#', ['class' => 'btn btn-default','onclick'=>'history.go(-1)']) ?>
        <?php
            /**
             * 编辑 按钮显示必须满足以下条件：
             * 1、拥有编辑的权限
             * 2、状态必须是在【制作中】
             * 3、创建者是自己
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_UPDATE) && $model->getIsStatusAssign() 
               && $model->create_by == Yii::$app->user->id)
                echo Html::a('编辑', ['update', 'id' => $model->id], ['class' =>'btn btn-primary']).' ';
            /**
             * 取消 按钮显示必须满足以下条件：
             * 1、拥有取消的权限
             * 2、状态必须是在【制作中】
             * 3、创建者是自己
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CANCEL) && $model->getIsStatusWorking()
               && $model->create_by == Yii::$app->user->id)
                echo Html::a('取消', ['cancel', 'id' => $model->id], ['class' =>'btn btn-danger']).' ';
            /**
             * 完成 按钮显示必须满足以下条件：
             * 1、状态必须是在【待审核】
             * 2、创建者是自己
             */
            if($model->getIsStatusWaitCheck() && $model->create_by == Yii::$app->user->id)
                echo Html::a('完成', 'javascript:;', ['id' => 'complete', 'class' =>'btn btn-success']).' ';
            /**
             * 添加审核 按钮显示必须满足以下条件：
             * 1、必须拥有添加审核权限
             * 2、状态必须是在【待审核】
             * 3、创建者是自己
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CREATE_CHECK) 
               && $model->getIsStatusWaitCheck() && $model->create_by == Yii::$app->user->id
               && (empty($model->multimediaChecks) || $multimedia->getIsCheckStatus($model->id)))
                echo Html::a('添加审核', ['check/create', 'task_id' => $model->id], ['class' =>'btn btn-info']).' ';
            /**
             * 恢复制作 按钮显示必须满足以下条件：
             * 1、状态必须是在【已完成】
             * 2、创建者是自己
             */
            if($model->getIsStatusCompleted() && $model->create_by == Yii::$app->user->id)
                echo Html::a('恢复制作', ['recovery', 'id' => $model->id], ['class' =>'btn btn-danger']).' ';
            /**
             * 指派 按钮显示必须满足以下条件：
             * 1、必须拥有指派权限
             * 2、状态必须是在【待指派】
             * 3、必须是创建者所在团队的指派人
             * 4、必须是在取消支撑下
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_ASSIGN) && $model->getIsStatusAssign()
               && $multimedia->getIsAssignPerson($model->make_team))
                echo Html::a('指派', 'javascript:;', ['id' => 'submit', 'class' =>'btn btn-primary']).' ';
            /**
             * 寻求支撑 按钮显示必须满足以下条件：
             * 1、状态必须是在【待指派】
             * 2、必须是创建者所在团队的指派人
             * 3、制作人员必须为空
             */
            if($model->getIsStatusAssign() && $multimedia->getIsAssignPerson($model->create_team) && empty($producer))
                echo Html::a('寻求支撑', 'javascript:;',  ['id' => 'seek-brace', 'class' =>'btn btn-danger']);
            /**
             * 取消支撑 按钮显示必须满足以下条件：
             * 1、必须是在已经寻求支撑下
             * 2、状态必须是在【待指派】
             * 3、必须是创建者所在团队的指派人
             */
            if($model->brace_mark == MultimediaTask::SEEK_BRACE_MARK && $model->getIsStatusAssign()
               && $multimedia->getIsAssignPerson($model->create_team))
                echo Html::a('取消支撑', ['cancel-brace', 'id' => $model->id], ['class' =>'btn btn-danger']).' ';
            /**
             * 开始 按钮显示必须满足以下条件：
             * 1、状态必须是在【待开始】
             * 2、必须是制作人身份
             */
            if($model->getIsStatusTostart() && $multimedia->getIsProducer($model->id))
                echo Html::a('开始', ['start', 'id' => $model->id], ['class' =>'btn btn-primary']).' ';
            /**
             * 提交 按钮显示必须满足以下条件：
             * 1、状态必须是在【制作中】
             * 2、必须是制作人身份
             */
            if($model->getIsStatusWorking() && $multimedia->getIsProducer($model->id))
                echo Html::a('提交', ['submit', 'id' => $model->id], ['class' =>'btn btn-success']).' ';
            /**
             * 提交审核 按钮显示必须满足以下条件：
             * 1、状态必须是在【待审核】
             * 2、必须是制作人身份
             * 3、审核记录不能为空
             */
            if($model->getIsStatusWaitCheck() && $multimedia->getIsProducer($model->id) 
               && !empty($model->multimediaChecks) && !$multimedia->getIsCheckStatus($model->id))
                echo Html::a('提交审核', ['check/submit', 'task_id' => $model->id], ['class' =>'btn btn-danger']).' ';
        ?>
    </div>
</div>

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