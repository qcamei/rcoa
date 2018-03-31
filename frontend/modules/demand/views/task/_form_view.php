<?php

use common\models\demand\DemandTask;
use wskeee\rbac\components\ResourceHelper;

/* @var $model DemandTask */

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
                    'options' => ['class' => 'btn btn-default', 'onclick'=> 'history.go(-1);return false'],
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
                    'conditions' => $model->create_by == Yii::$app->user->id && ($model->getIsStatusDefault() || $model->getIsStatusAdjusimenting()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '提交审核',
                    'url' => ['check/create', 'task_id' => $model->id],
                    'options' => ['id' => 'check-create', 'class' => 'btn btn-info'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->create_by == Yii::$app->user->id && ($model->getIsStatusDefault() || $model->getIsStatusAdjusimenting()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '取消',
                    'url' => ['cancel', 'id' => $model->id],
                    'options' => ['id' => 'cancel', 'class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->create_by == Yii::$app->user->id && $model->getIsStatusDevelopingBefore(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '验收',
                    'url' => ['acceptance/create', 'task_id' => $model->id],
                    'options' => ['id' => 'acceptance-create', 'class' => 'btn btn-success'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->create_by == Yii::$app->user->id && ($model->getIsStatusAcceptance() || $model->getIsStatusAcceptanceing()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '调整绩效',
                    'url' => ['acceptance/create', 'task_id' => $model->id, 'pass' => 1],
                    'options' => ['class' => 'btn btn-success'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->create_by == Yii::$app->user->id && $model->getIsStatusAppealing(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '驳回申诉',
                    'url' => ['appeal-reply/create', 'task_id' => $model->id],
                    'options' => ['id' => 'appealReply-create', 'class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->create_by == Yii::$app->user->id && $model->getIsStatusAppealing(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '查看开发',
                    'url' => ['check-dev', 'id' => $model->id],
                    'options' => ['class' =>'btn btn-primary', 'target' => '_blank'],
                    'symbol' => '&nbsp;',
                    'conditions' => !empty($model->undertake_person),//承接人不为空时显示
                    'adminOptions' => true,
                ],
                //审核人角色按钮组
                [
                    'name' => '通过',
                    'url' => ['check-reply/create', 'task_id' => $model->id, 'pass' => 1],
                    'options' => ['id' => 'checkReply-create-1', 'class' =>'btn btn-success'],
                    'symbol' => '&nbsp;',
                    'conditions' => $isAuditor && ($model->getIsStatusCheck() || $model->getIsStatusChecking()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '不通过',
                    'url' => ['check-reply/create', 'task_id' => $model->id, 'pass' => 0],
                    'options' => ['id' => 'checkReply-create-0', 'class' =>'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $isAuditor && ($model->getIsStatusCheck() || $model->getIsStatusChecking()),
                    'adminOptions' => true,
                ],
                //承接人角色按钮组
                [
                    'name' => '承接',
                    'url' => ['undertake', 'id' => $model->id],
                    'options' => ['id' => 'undertake', 'class' =>'btn btn-primary'],
                    'symbol' => '&nbsp;',
                    'conditions' => $isUndertaker && $model->getIsStatusUndertake(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '创建开发',
                    'url' => ['/teamwork/course/create', 'demand_task_id' => $model->id],
                    'options' => ['id' => 'create-develop', 'class' =>'btn btn-primary'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->undertake_person == Yii::$app->user->id && empty($model->teamworkCourse) && $model->getIsStatusDeveloping(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '交付',
                    'url' => ['delivery/create', 'task_id' => $model->id],
                    'options' => ['id' => 'delivery-create', 'class' =>'btn btn-success'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->undertake_person == Yii::$app->user->id && !empty($model->teamworkCourse) && ($model->getIsStatusDeveloping() || $model->getIsStatusUpdateing()),
                    'adminOptions' => true,
                ],
                [
                    'name' => '确定',
                    'url' => ['wait-confirm', 'id' => $model->id],
                    'options' => ['id' => 'wait-confirm', 'class' => 'btn btn-success'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->undertake_person == Yii::$app->user->id && $model->getIsStatusWaitConfirm(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '申诉',
                    'url' => ['appeal/create', 'task_id' => $model->id],
                    'options' => ['id' => 'appeal-create', 'class' => 'btn btn-info'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->undertake_person == Yii::$app->user->id && $model->getIsStatusWaitConfirm(),
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