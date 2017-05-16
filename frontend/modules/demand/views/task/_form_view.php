<?php

use common\models\demand\DemandAcceptance;
use common\models\demand\DemandTask;
use frontend\modules\demand\utils\DemandTool;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

/* @var $model DemandTask */
/* @var $da_model DemandAcceptance*/
/* @var $dtTool DemandTool */ 
/* @var $rbacManager RbacManager */  

$page = [
    'index', 
    'create_by' => Yii::$app->user->id,
    'undertake_person' => Yii::$app->user->id, 
    'auditor' => Yii::$app->user->id,
];
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
            if(\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_EDIT)){
                echo Html::a('编辑', ['update', 'id' => $model->id], ['class' =>'btn btn-primary']).' ';
            }else{
                if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_UPDATE) && ($model->getIsStatusDefault() || $model->getIsStatusAdjusimenting())
                   && $model->create_by == Yii::$app->user->id)
                    echo Html::a('编辑', ['update', 'id' => $model->id], ['class' =>'btn btn-primary']).' ';
            }
               
            /**
             * 提交审核 按钮显示必须满足以下条件：
             * 1、状态必须是在【默认】
             * 2、创建者是自己
             */
            if($model->getIsStatusDefault() && $model->create_by == Yii::$app->user->id)
                echo Html::a('提交审核', ['submit-check', 'id' => $model->id], ['id' => 'task-submit-check', 'class' => 'btn btn-info']).' ';
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
               && $model->getIsStatusAdjusimenting() && $model->create_by == Yii::$app->user->id )
                echo Html::a('提交审核', ['check/submit', 'task_id' => $model->id], ['class' =>'btn btn-info']).' ';
            
            /**
             * 验收 按钮显示必须满足以下条件：
             * 1、拥有完成的权限
             * 2、状态必须是在【待验收】 or 【验收中】
             * 3、创建者是自己
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_COMPLETE) 
               && ($model->getIsStatusAcceptance() || $model->getIsStatusAcceptanceing()) && $model->create_by == Yii::$app->user->id)
                echo Html::a('验收', ['acceptance/create', 'demand_task_id' => $model->id], ['id' => 'complete', 'class' =>'btn btn-success']).' ';
           
            /**
             * 同意申诉 按钮显示必须满足以下条件：
             * 1、状态必须是在【申诉中】
             * 2、创建者必须是自己
             */
            if($model->getIsStatusAppealing() && $model->create_by == Yii::$app->user->id)
                echo Html::a('同意申诉', ['acceptance/create', 'demand_task_id' => $model->id, 'pass' => 1], ['class' => 'btn btn-success']).' ';
            
            /**
             * 不同意 按钮显示必须满足以下条件：
             * 1、状态必须是在【申诉中】
             * 2、创建者必须是自己
             */
            if($model->getIsStatusAppealing() && $model->create_by == Yii::$app->user->id)
                echo Html::a('不同意', ['reply/create', 'demand_task_id' => $model->id], ['id' => 'reply-create', 'class' => 'btn btn-danger']).' ';
            
            /**
             * 课程开发 按钮显示必须满足以下条件：
             * 1、拥有完成的权限
             * 2、状态必须是在【待验收】 or 【验收中】
             * 3、创建者是自己
             */
            if(($model->getIsStatusAcceptance() || $model->getIsStatusAcceptanceing()) && $model->create_by == Yii::$app->user->id )
                echo Html::a('课程开发', ['/teamwork/course/view', 'demand_task_id' => $model->id], ['class' =>'btn btn-primary', 'target' => '_blank']).' ';
            
            
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
             * 3、必须是【承接人】
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_UNDERTAKE) 
              && $rbacManager->isRole(RbacName::ROLE_DEMAND_UNDERTAKE_PERSON, Yii::$app->user->id) && $model->getIsStatusUndertake())
                echo Html::a('承接', ['undertake', 'id' => $model->id],  ['id' => 'undertake', 'class' =>'btn btn-primary']).' ';
            /**
             * 创建开发 按钮显示必须满足以下条件：
             * 1、必须拥有课程开发权限
             * 2、状态必须是在【开发中】
             * 3、必须是该需求任务的承接人
             * 4、课程开发数据必须为空
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_DEVELOP) && $model->getIsStatusDeveloping()
               && $model->undertake_person == Yii::$app->user->id && empty($model->teamworkCourse))
                echo Html::a('创建开发', ['/teamwork/course/create', 'demand_task_id' => $model->id], ['id' => 'create-develop', 'class' =>'btn btn-primary']).' ';
            /**
             * 验收 按钮显示必须满足以下条件：
             * 1、状态必须是在【开发中】
             * 2、必须是该任务的开发负责人
             * 3、课程开发数据必须非空
             */
            if($model->getIsStatusDeveloping() 
               && $model->developPrincipals->u_id == Yii::$app->user->id && !empty($model->teamworkCourse))
                echo Html::a('交付', ['delivery/create', 'demand_task_id' => $model->id], ['id' => 'submit-task', 'class' =>'btn btn-success']).' ';
            /**
             * 提交验收 按钮显示必须满足以下条件：
             * 1、必须拥有提交验收权限
             * 2、状态必须是在【修改中】
             * 3、必须是该任务的开发负责人
             */
            if($model->getIsStatusUpdateing() && (Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_SUBMIT_ACCEPTANCE)
                && $model->developPrincipals->u_id == Yii::$app->user->id))
                echo Html::a('交付', ['delivery/create', 'demand_task_id' => $model->id], ['class' =>'btn btn-info']).' ';
            
            /**
             * 确定 按钮显示必须满足以下条件：
             * 1、状态必须是在【待确定】
             * 2、必须是该任务的开发负责人
             */
            if($model->getIsStatusWaitConfirm() && $model->developPrincipals->u_id == Yii::$app->user->id)
                echo Html::a('确定', ['wait-confirm', 'id' => $model->id], ['id' => 'wait-confirm', 'class' => 'btn btn-success']).' ';
            
            /**
             * 确定 按钮显示必须满足以下条件：
             * 1、状态必须是在【待确定】
             * 2、必须是该任务的开发负责人
             */
            if($model->getIsStatusWaitConfirm() && $model->developPrincipals->u_id == Yii::$app->user->id)
                echo Html::a('申诉', ['appeal/create', 'demand_task_id' => $model->id], ['id' => 'appeal-create', 'class' => 'btn btn-info']).' ';
        ?>
        </div>
    </div>
</div>