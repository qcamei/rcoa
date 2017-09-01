<?php

use common\models\worksystem\WorksystemTask;
use wskeee\rbac\components\ResourceHelper;
use wskeee\rbac\RbacManager;
use yii\helpers\Html;

/* @var $model WorksystemTask */
/* @var $rbacManager RbacManager */  

?>

<div class="controlbar">
    <div class="container">
        <div class="footer-view-btn">
        <?php
            /**
             * $buttonHtml = [
             *     [
             *         name  => 按钮名称，
             *         url  =>  按钮url，
             *         options  => 按钮属性，
             *         symbol => html字符符号：&nbsp;，
             *         conditions  => 按钮显示条件，
             *         adminOptions  => 按钮管理选项，
             *     ],
             * ]
             */
            $buttonHtml = [
                [
                    'name' => Yii::t('rcoa', 'Back'),
                    'url' => ['back'],
                    'options' => ['class' => 'btn btn-default', 'onclick'=> 'history.go(-1)'],
                    'symbol' => '&nbsp;',
                    'conditions' => true,
                    'adminOptions' => true,
                ],
                //发布人角色按钮组
                [
                    'name' => '编辑',
                    'url' => ['update', 'id' => $model->id],
                    'options' => ['class' => 'btn btn-primary'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->create_by == Yii::$app->user->id && ($model->getIsStatusDefault() || $model->getIsStatusAdjustmenting()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '提交审核',
                    'url' => ['submit-check', 'task_id' => $model->id],
                    'options' => ['id' => 'check-submit', 'class' => 'btn btn-info'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->create_by == Yii::$app->user->id && ($model->getIsStatusDefault() || $model->getIsStatusAdjustmenting()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '验收通过',
                    'url' => ['complete-acceptance', 'task_id' => $model->id],
                    'options' => ['id' => 'acceptance-complete', 'class' => 'btn btn-success'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->create_by == Yii::$app->user->id && ($model->getIsStatusWaitAcceptance() || $model->getIsStatusAcceptanceing()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '添加修改',
                    'url' => ['create-acceptance', 'task_id' => $model->id],
                    'options' => ['id' => 'acceptance-create', 'class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->create_by == Yii::$app->user->id && ($model->getIsStatusWaitAcceptance() || $model->getIsStatusAcceptanceing()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '取消',
                    'url' => ['cancel', 'id' => $model->id],
                    'options' => ['id' => 'cancel', 'class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->create_by == Yii::$app->user->id && ($model->status < WorksystemTask::STATUS_WORKING),
                    'adminOptions' => true,
                ],
                //指派人角色按钮组
                [
                    'name' => '指派',
                    'url' => ['create-assign', 'task_id' => $model->id],
                    'options' => ['id' => 'assign-create', 'class' => 'btn btn-primary'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->getIsSeekBrace() || $model->getIsSeekEpiboly() ? !$isHaveAssign && $model->getIsCancelEpiboly() && $model->getIsStatusWaitAssign() : $isHaveAssign && ($model->getIsStatusWaitCheck() || $model->getIsStatusChecking()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '寻求支撑',
                    'url' => ['create-brace', 'task_id' => $model->id],
                    'options' => ['id' => 'brace-create', 'class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $isHaveAssign && $model->getIsCancelBrace() && $model->getIsCancelEpiboly() && ($model->getIsStatusWaitCheck() || $model->getIsStatusChecking() || $model->getIsStatusWaitAssign()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '外包',
                    'url' => ['create-epiboly', 'task_id' => $model->id],
                    'options' => ['id' => 'epiboly-create', 'class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $isHaveAssign && $model->getIsCancelBrace() && $model->getIsCancelEpiboly() && ($model->getIsStatusWaitCheck() || $model->getIsStatusChecking() || $model->getIsStatusWaitAssign()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '审核不通过',
                    'url' => ['create-check', 'task_id' => $model->id],
                    'options' => ['id' => 'check-create', 'class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $isHaveAssign && $model->getIsCancelBrace() && $model->getIsCancelEpiboly() && ($model->getIsStatusWaitCheck() || $model->getIsStatusChecking() || $model->getIsStatusWaitAssign()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '取消支撑',
                    'url' => ['cancel-brace', 'task_id' => $model->id],
                    'options' => ['id' => 'brace-cancel', 'class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $isHaveAssign && $model->getIsSeekBrace() && $model->getIsStatusWaitAssign(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '取消外包',
                    'url' => ['cancel-epiboly', 'task_id' => $model->id],
                    'options' => ['id' => 'epiboly-cancel', 'class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $isHaveAssign && $model->getIsSeekEpiboly() && $model->getIsStatusWaitUndertake(),
                    'adminOptions' => true,
                ],
                //制作人角色按钮组
                [
                    'name' => '开始',
                    'url' => ['start-make', 'task_id' => $model->id],
                    'options' => ['id' => 'start-make', 'class' => 'btn btn-primary'],
                    'symbol' => '&nbsp;',
                    'conditions' => $isHaveMake && $model->getIsStatusToStart(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '承接',
                    'url' => ['create-undertake', 'task_id' => $model->id],
                    'options' => ['id' => 'undertake-create', 'class' => 'btn btn-primary'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->getIsStatusWaitUndertake() && $model->getIsSeekEpiboly(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '取消承接',
                    'url' => ['cancel-undertake', 'task_id' => $model->id],
                    'options' => ['id' => 'undertake-cancel', 'class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $isHaveMake && $model->getIsStatusToStart() && $model->getIsSeekEpiboly(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '提交验收',
                    'url' => ['contentinfo/submit', 'task_id' => $model->id],
                    'options' => ['id' => 'acceptance-submit', 'class' => 'btn btn-info'],
                    'symbol' => '&nbsp;',
                    'conditions' => $isHaveMake && ($model->getIsStatusWorking() || $model->getIsStatusUpdateing()),
                    'adminOptions' => true,
                ],
            ];
            
            
            foreach ($buttonHtml as $item) {
                echo ResourceHelper::a($item['name'], $item['url'], $item['options'], $item['conditions']).($item['conditions'] ? $item['symbol'] : null);
            }
            
        ?>
        </div>
    </div>
</div>