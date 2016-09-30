<?php

use common\models\multimedia\MultimediaTask;
use frontend\modules\multimedia\MultimediaTool;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

/* @var $model MultimediaTask */
/* @var $multimedia MultimediaTool */

?>

<div class="controlbar">
    <div class="container">
        <div class="footer-view-btn">
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
             * 完成 按钮显示必须满足以下条件：
             * 1、状态必须是在【待审核】
             * 2、创建者是自己
             */
            if($model->getIsStatusWaitCheck() && $model->create_by == Yii::$app->user->id)
                echo Html::a('完成', 'javascript:;', ['id' => 'complete', 'class' =>'btn btn-success']).' ';
            /**
             * 取消 按钮显示必须满足以下条件：
             * 1、拥有取消的权限
             * 2、状态必须非在【待审核】or【已完成】or【已取消】
             * 3、创建者是自己
             * 4、必须是在取消支撑下
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CANCEL) 
               && !($model->getIsStatusWaitCheck() || $model->getIsStatusCompleted() || $model->getIsStatusCancel()) 
               && $model->create_by == Yii::$app->user->id /*&& $model->brace_mark == MultimediaTask::CANCEL_BRACE_MARK*/)
                echo Html::a('取消', 'javascript:;', ['id' => 'cancel', 'class' =>'btn btn-danger', 'style' => 'margin-right:15px;']).' ';
            /**
             * 添加审核 按钮显示必须满足以下条件：
             * 1、必须拥有添加审核权限
             * 2、状态必须是在【待审核】
             * 3、创建者是自己
             * 4、审核记录必须为空
             * 5、审核记录状态必须已完成
             */
            if(Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CREATE_CHECK) 
               && $model->getIsStatusWaitCheck() && $model->create_by == Yii::$app->user->id
               && (empty($model->multimediaChecks) || !$multimedia->getIsCheckStatus($model->id)))
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
                echo Html::a('指派', 'javascript:;', ['id' => 'submit', 'class' =>'btn btn-success']).' ';
            /**
             * 寻求支撑 按钮显示必须满足以下条件：
             * 1、必须是在取消支撑下
             * 2、状态必须是在【待指派】
             * 3、必须是创建者所在团队的指派人
             * 4、制作人员必须为空
             */
            if($model->brace_mark == MultimediaTask::CANCEL_BRACE_MARK && $model->getIsStatusAssign() 
              && $multimedia->getIsAssignPerson($model->create_team) && empty($producer))
                echo Html::a('寻求支撑', 'javascript:;',  ['id' => 'seek-brace', 'class' =>'btn btn-danger']).' ';
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
             * 4、审核记录状态必须未完成
             */
            if($model->getIsStatusWaitCheck() && $multimedia->getIsProducer($model->id) 
               && !empty($model->multimediaChecks) && $multimedia->getIsCheckStatus($model->id))
                echo Html::a('提交审核', ['check/submit', 'task_id' => $model->id], ['class' =>'btn btn-danger']).' ';
        ?>
        </div>
    </div>
</div>