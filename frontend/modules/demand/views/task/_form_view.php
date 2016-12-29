<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\utils\DemandTool;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

/* @var $model DemandTask */
/* @var $dtTool DemandTool */ 

$page = [
    'index', 
    'create_by' => Yii::$app->user->id,
    'status' => DemandTask::STATUS_DEFAULT
];
?>

<div class="controlbar">
    <div class="container">
        <div class="footer-view-btn">
        <?= Html::a(Yii::t('rcoa', 'Back'), $page, ['class' => 'btn btn-default', /*'onclick'=> 'history.go(-1)'*/]) ?>
        <?php
            //发布人
            /**
             * 编辑 按钮显示必须满足以下条件：
             * 1、拥有编辑的权限
             * 2、状态必须是在【调整中】
             * 3、创建者是自己
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_UPDATE) && $model->getIsStatusAdjusimenting() 
               && $model->create_by == Yii::$app->user->id)
                echo Html::a('编辑', ['update', 'id' => $model->id], ['class' =>'btn btn-primary']).' ';
            /**
             * 取消 按钮显示必须满足以下条件：
             * 1、拥有取消的权限
             * 2、状态必须非在【待审核】or【调整中】or【待承接】
             * 3、创建者是自己
             * 4、必须是在取消支撑下
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CANCEL) 
               && ($model->getIsStatusCheck() || $model->getIsStatusUndertake()) 
               && $model->create_by == Yii::$app->user->id)
                echo Html::a('取消', ['cancel', 'id' => $model->id], ['id' => 'cancel', 'class' =>'btn btn-danger', 'style' => 'margin-right:15px;']).' ';
            /**
             * 提交审核 按钮显示必须满足以下条件：
             * 1、必须拥有提交审核权限
             * 2、状态必须是在【调整中】
             * 3、创建者是自己
             * 4、审核记录不能为空
             * 5、审核记录状态必须未完成
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_SUBMIT_CHECK) 
               && $model->getIsStatusAdjusimenting() && $model->create_by = Yii::$app->user->id )
                echo Html::a('提交审核', ['check/submit', 'task_id' => $model->id], ['class' =>'btn btn-info']).' ';
            /**
             * 完成 按钮显示必须满足以下条件：
             * 1、拥有完成的权限
             * 2、状态必须是在【待验收】 or 【验收中】
             * 3、创建者是自己
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_COMPLETE) 
               && ($model->getIsStatusAcceptance() || $model->getIsStatusAcceptanceing()) && $model->create_by == Yii::$app->user->id)
                echo Html::a('完成', ['complete', 'id' => $model->id], ['id' => 'complete', 'class' =>'btn btn-success']).' ';
            /**
             * 验收不通过 按钮显示必须满足以下条件：
             * 1、拥有完成的权限
             * 2、状态必须是在【待验收】 or 【验收中】
             * 3、创建者是自己
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE_ACCEPTANCE) 
               && ($model->getIsStatusAcceptance() || $model->getIsStatusAcceptanceing()) && $model->create_by == Yii::$app->user->id)
                echo Html::a('验收不通过', ['acceptance/create', 'task_id' => $model->id], ['id' => 'acceptance-create', 'class' =>'btn btn-danger']).' ';
            /**
             * 验收不通过 按钮显示必须满足以下条件：
             * 1、拥有完成的权限
             * 2、状态必须是在【待验收】 or 【验收中】
             * 3、创建者是自己
             */
            if(($model->getIsStatusAcceptance() || $model->getIsStatusAcceptanceing()) && $model->create_by == Yii::$app->user->id )
                echo Html::a('课程开发', ['/teamwork/course/view', 'demand_task_id' => $model->id], ['class' =>'btn btn-primary', 'target' => '_blank']).' ';
            /**
             * 恢复 按钮显示必须满足以下条件：
             * 1、拥有恢复权限
             * 2、状态必须是在【已完成】
             * 3、创建者是自己
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_RESTORE) 
               && $model->getIsStatusCompleted() && $model->create_by == Yii::$app->user->id)
                echo Html::a('恢复', ['recovery', 'id' => $model->id], ['class' =>'btn btn-danger']).' ';
            
            //审核人
            /**
             * 通过审核 按钮显示必须满足以下条件：
             * 1、状态必须是在【待审核】 or 【审核中】
             * 2、必须是审核人
             * 3、审核记录状态必须已完成
             */
            if(($model->getIsStatusCheck() || $model->getIsStatusChecking()) && $dtTool->getIsAuditor($model->create_team))
                echo Html::a('通过审核', ['pass-check', 'id' => $model->id], ['class' =>'btn btn-success']).' ';
            /**
             * 审核不通过 按钮显示必须满足以下条件：
             * 1、必须拥有添加审核权限
             * 2、状态必须是在【待审核】 or 【审核中】
             * 3、必须是审核人
             * 4、审核记录必须为空
             * 5、审核记录状态必须已完成
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE_CHECK) 
               && ($model->getIsStatusCheck() || $model->getIsStatusChecking())&& $dtTool->getIsAuditor($model->create_team))
                echo Html::a('审核不通过', ['check/create', 'task_id' => $model->id], ['id' => 'check-create', 'class' =>'btn btn-danger']).' ';
            
            //承接人
            /**
             * 承接 按钮显示必须满足以下条件：
             * 1、必须拥有承接权限
             * 2、状态必须是在【待承接】
             * 3、必须是所有团队的【开发经理】
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_UNDERTAKE) && $model->getIsStatusUndertake() 
              && $dtTool->getIsUndertakePerson())
                echo Html::a('承接', ['undertake', 'id' => $model->id],  ['id' => 'undertake', 'class' =>'btn btn-primary']).' ';
            /**
             * 创建开发 按钮显示必须满足以下条件：
             * 1、必须拥有课程开发权限
             * 2、状态必须是在【开发中】
             * 3、必须是该需求任务的承接人
             * 4、课程开发数据必须为空
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_DEVELOP) && $model->getIsStatusDeveloping()
               && $model->undertakePerson->u_id == Yii::$app->user->id && empty($model->teamworkCourse))
                echo Html::a('创建开发', ['/teamwork/course/create', 'demand_task_id' => $model->id], ['class' =>'btn btn-primary']).' ';
            /**
             * 验收 按钮显示必须满足以下条件：
             * 1、状态必须是在【开发中】
             * 2、必须是该任务的承接人
             * 3、课程开发数据必须非空
             */
            if($model->getIsStatusDeveloping() && $model->undertakePerson->u_id == Yii::$app->user->id && !empty($model->teamworkCourse))
                echo Html::a('提交任务', ['submit-task', 'id' => $model->id], ['id' => 'submit-task', 'class' =>'btn btn-success']).' ';
            /**
             * 提交验收 按钮显示必须满足以下条件：
             * 1、必须拥有提交验收权限
             * 2、状态必须是在【修改中】
             * 3、必须是该任务的承接人
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_SUBMIT_ACCEPTANCE) 
               && $model->getIsStatusUpdateing() && $model->undertakePerson->u_id == Yii::$app->user->id)
                echo Html::a('提交验收', ['acceptance/submit', 'task_id' => $model->id], ['class' =>'btn btn-info']).' ';
        ?>
        </div>
    </div>
</div>