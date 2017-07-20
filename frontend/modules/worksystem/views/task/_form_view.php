<?php

use common\models\worksystem\WorksystemTask;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

/* @var $model WorksystemTask */
/* @var $rbacManager RbacManager */  

?>

<div class="controlbar">
    <div class="container">
        <div class="footer-view-btn">
        <?= Html::a(Yii::t('rcoa', 'Back'), '#', ['class' => 'btn btn-default', 'onclick'=> 'history.go(-1)']) ?>
        <?php
            //发布人
            /**
             * 编辑 按钮显示必须满足以下条件：
             * 1、拥有编辑的权限
             * 2、状态必须是在【默认】 or【调整中】
             * 3、创建者是自己
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_ADMIN_EDIT)){
                echo Html::a('编辑', ['update', 'id' => $model->id], ['class' =>'btn btn-primary']).' ';
            }else{
                if((Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_TASK_UPDATE) && $model->create_by == Yii::$app->user->id) && ($model->getIsStatusDefault() || $model->getIsStatusAdjustmenting()))
                    echo Html::a('编辑', ['update', 'id' => $model->id], ['class' =>'btn btn-primary']).' ';
            }
            /**
             * 提交审核 按钮显示必须满足以下条件：
             * 1、拥有提交审核的权限
             * 2、状态必须是在【默认】 or 【调整中】
             * 3、创建者是自己
             */
            if((Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_SUBMIT_CHECK) && $model->create_by == Yii::$app->user->id) && ($model->getIsStatusDefault() || $model->getIsStatusAdjustmenting()))
                echo Html::a('提交审核', ['submit-check', 'task_id' => $model->id], ['id' => 'check-submit', 'class' => 'btn btn-info']). ' ';
            /**
             * 验收通过 按钮显示必须满足以下条件：
             * 1、拥有验收的权限
             * 2、状态必须是在【待验收】 or 【验收中】
             * 3、创建者是自己
             */
            if((Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_TASK_ACCEPTANCE) && $model->create_by == Yii::$app->user->id) && ($model->getIsStatusWaitAcceptance() || $model->getIsStatusAcceptanceing()))
                echo Html::a('验收通过', ['complete-acceptance', 'task_id' => $model->id], ['id' => 'acceptance-complete', 'class' => 'btn btn-success']). ' ';
            /**
             * 验收不通过 按钮显示必须满足以下条件：
             * 1、拥有验收的权限
             * 2、状态必须是在【待验收】 or 【验收中】
             * 3、创建者是自己
             */
            if((Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_TASK_ACCEPTANCE) && $model->create_by == Yii::$app->user->id) && ($model->getIsStatusWaitAcceptance() || $model->getIsStatusAcceptanceing()))
                echo Html::a('验收不通过', ['create-acceptance', 'task_id' => $model->id], ['id' => 'acceptance-create', 'class' => 'btn btn-danger']). ' ';
            /**
             * 取消 按钮显示必须满足以下条件：
             * 1、拥有取消的权限
             * 2、必须创建人是自己
             * 3、状态必须是在【制作中】之前
             */
            if((Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_TASK_CANCEL) && $model->create_by == Yii::$app->user->id) && ($model->status < WorksystemTask::STATUS_WORKING))
                echo Html::a('取消', ['cancel', 'id' => $model->id], ['id' => 'cancel', 'class' => 'btn btn-danger']). ' ';
            
            //指派人
            /**
             * 指派 按钮显示必须满足以下条件：
             * 1、拥有指派的权限
             * 2、如果是【支撑】or【外包】自己不显示，否则自己才显示
             */
            if($model->getIsSeekBrace() || $model->getIsSeekEpiboly()){
                if(Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_CREATE_ASSIGN) && !$is_assigns && $model->getIsCancelEpiboly() && $model->getIsStatusWaitAssign())
                    echo Html::a('指派', ['create-assign', 'task_id' => $model->id], ['id' => 'assign-create', 'class' => 'btn btn-primary']).' '; 
            }else {
                if(Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_CREATE_ASSIGN) && $is_assigns && ($model->getIsStatusWaitCheck() || $model->getIsStatusChecking()))
                    echo Html::a('指派', ['create-assign', 'task_id' => $model->id], ['id' => 'assign-create', 'class' => 'btn btn-primary']).' '; 
            }
            
            /**
             * 寻求支撑 按钮显示必须满足以下条件：
             * 1、拥有指派的权限
             * 2、状态必须是在【待审核】 or 【审核中】 or 【待指派】
             * 3、必须是在【取消支撑】
             * 4、指派人是自己
             */
            if((Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_CREATE_ASSIGN) && $is_assigns && $model->getIsCancelBrace()) && ($model->getIsStatusWaitCheck() || $model->getIsStatusChecking() || $model->getIsStatusWaitAssign()))
                echo Html::a('寻求支撑', ['create-brace', 'task_id' => $model->id], ['id' => 'brace-create', 'class' => 'btn btn-danger']).' '; 
            /**
             * 外包 按钮显示必须满足以下条件：
             * 1、拥有指派的权限
             * 2、状态必须是在【待审核】 or 【审核中】 or 【待指派】
             * 3、必须是在【取消外包】
             * 3、指派人是自己
             */
            if((Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_CREATE_ASSIGN) && $is_assigns && $model->getIsCancelEpiboly()) && ($model->getIsStatusWaitCheck() || $model->getIsStatusChecking()))
                echo Html::a('外包', ['create-epiboly', 'task_id' => $model->id], ['id' => 'epiboly-create', 'class' => 'btn btn-danger']).' '; 
            /**
             * 审核不通过 按钮显示必须满足以下条件：
             * 1、拥有创建审核的权限
             * 2、状态必须是在【待审核】 or 【审核中】
             * 3、指派人是自己
             */
            if((Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_CREATE_CHECK) && $is_assigns && $model->getIsCancelBrace()) && ($model->getIsStatusWaitCheck() || $model->getIsStatusChecking()))
                echo Html::a('审核不通过', ['create-check', 'task_id' => $model->id], ['id' => 'check-create', 'class' => 'btn btn-danger']). ' ';
            /**
             * 取消支撑 按钮显示必须满足以下条件：
             * 1、拥有指派的权限
             * 2、状态必须是在【待审核】 or 【审核中】
             * 3、必须是在【寻求支撑】
             * 4、指派人是自己
             */
            if((Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_CREATE_ASSIGN) && $is_assigns && $model->getIsSeekBrace() && $model->getIsStatusWaitAssign()))
                echo Html::a('取消支撑', ['cancel-brace', 'task_id' => $model->id], ['id' => 'brace-cancel', 'class' => 'btn btn-danger']).' '; 
            /**
             * 取消支撑 按钮显示必须满足以下条件：
             * 1、拥有指派的权限
             * 2、状态必须是在【待审核】 or 【审核中】
             * 3、必须是在【寻求外包】
             * 4、指派人是自己
             */
            if((Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_CREATE_ASSIGN) && $is_assigns && $model->getIsSeekEpiboly() && $model->getIsStatusWaitUndertake()))
                echo Html::a('取消外包', ['cancel-epiboly', 'task_id' => $model->id], ['id' => 'epiboly-cancel', 'class' => 'btn btn-danger']).' '; 
            
            //制作人
            /**
             * 开始 按钮显示必须满足以下条件：
             * 1、必须是该任务的制作人
             * 2、状态必须是在【待开始】
             */
            if($is_producer && $model->getIsStatusToStart())
                echo Html::a('开始', ['start-make', 'task_id' => $model->id], ['id' => 'start-make', 'class' => 'btn btn-primary']).' '; 
            /**
             * 承接 按钮显示必须满足以下条件：
             * 1、必须拥有承接权限
             * 2、状态必须是在【待承接】
             * 3、必须是【寻求外包】
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_TASK_UNDERTAKE) && $model->getIsStatusWaitUndertake() && $model->getIsSeekEpiboly())
                echo Html::a('承接', ['create-undertake', 'task_id' => $model->id], ['id' => 'undertake-create', 'class' => 'btn btn-primary']).' '; 
            /**
             * 取消承接 按钮显示必须满足以下条件：
             * 1、必须拥有承接权限
             * 2、该任务属于自己
             * 3、状态必须是在【待开始】
             * 4、必须是【寻求外包】
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_TASK_UNDERTAKE) && $is_producer && $model->getIsStatusToStart() && $model->getIsSeekEpiboly())
                echo Html::a('取消承接', ['cancel-undertake', 'task_id' => $model->id], ['id' => 'undertake-cancel', 'class' => 'btn btn-danger']).' '; 
            /**
             * 提交验收 按钮显示必须满足以下条件：
             * 1、拥有提交验收权限
             * 2、状态必须是在【制作中】 or 【修改中】
             * 3、该任务的制作人必须是自己
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_SUBMIT_ACCEPTANCE) && ($is_producer && $model->getIsStatusWorking() || $model->getIsStatusUpdateing()))
                echo Html::a('提交验收', ['contentinfo/submit', 'task_id' => $model->id], ['id' => 'acceptance-submit', 'class' => 'btn btn-info']).' '; 
        ?>    
        </div>
    </div>
</div>