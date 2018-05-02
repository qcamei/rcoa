<?php

use common\models\scene\SceneBook;
use wskeee\rbac\components\ResourceHelper;
use wskeee\rbac\RbacManager;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var $model SceneBook */
/* @var $rbacManager RbacManager */  

?>

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
//        [
//            'name' => Yii::t('rcoa', 'Back'),
//            'url' => ['index', 'site_id' => $model->site_id, 'date' => $model->date, 'date_switch' => $model->date_switch],
//            'options' => ['class' => 'btn btn-default', /*'onclick'=> 'history.go(-1);return false'*/],
//            'symbol' => '&nbsp;',
//            'conditions' => true,
//            'adminOptions' => true,
//        ],
        //预约人角色按钮组
        [
            'name' => '编辑',
            'url' => ['update', 'id' => $model->id],
            'options' => ['class' => 'btn btn-primary'],
            'symbol' => '&nbsp;',
            'conditions' => ($model->getIsAssign() || $model->getIsStausShootIng()) && $model->booker_id == Yii::$app->user->id
                            && date('Y-m-d H:i:s', strtotime($model->date.$model->start_time)) > date('Y-m-d H:i:s', time()) && !$model->is_transfer,
            'adminOptions' => true,
        ],
        [
            'name' => '申请转让',
            'url' => ['transfer', 'id' => $model->id],
            'options' => ['id' => 'transfer', 'class' => 'btn btn-danger', 'onclick' => 'myModal($(this));return false;'],
            'symbol' => '&nbsp;',
            'conditions' => $model->getIsTransfer() && !$model->is_transfer && $model->booker_id == Yii::$app->user->id 
                            && date('Y-m-d H:i:s', strtotime($model->date.$model->start_time)) > date('Y-m-d H:i:s', time()),
            'adminOptions' => true,
        ],
        [
            'name' => '预约',
            'url' => ['receive', 'id' => $model->id],
            'options' => ['id' => 'receive', 'class' => 'btn btn-primary', 'onclick' => 'myModal($(this));return false;'],
            'symbol' => '&nbsp;',
            'conditions' => $model->getIsTransfer() && $model->is_transfer && $model->booker_id != Yii::$app->user->id 
                            && date('Y-m-d H:i:s', strtotime($model->date.$model->start_time)) > date('Y-m-d H:i:s', time()),
            'adminOptions' => true,
        ],
        [
            'name' => '取消转让',
            'url' => ['cancel-transfer', 'id' => $model->id],
            'options' => ['id' => 'cancel-transfer', 'class' => 'btn btn-danger', 'onclick' => 'myModal($(this));return false;'],
            'symbol' => '&nbsp;',
            'conditions' => $model->getIsTransfer() && $model->is_transfer && $model->booker_id == Yii::$app->user->id,
            'adminOptions' => true,
        ],
        //摄影组长角色按钮组
        [
            'name' => '指派',
            'url' => ['assign', 'id' => $model->id],
            'options' => ['class' => 'btn btn-success', 'onclick' => 'myModal($(this));return false;'],
            'symbol' => '&nbsp;',
            'conditions' => ($model->getIsAssign() || $model->getIsStausShootIng())
                            && date('Y-m-d H:i:s', strtotime($model->date.$model->start_time)) > date('Y-m-d H:i:s', time()),
            'adminOptions' => true,
        ],
        //接洽人和摄影师角色按钮
        [
            'name' => '评价',
            'url' => ['appraise/create', 'book_id' => $model->id, 'role' => isset($roleUsers[Yii::$app->user->id]) ? $roleUsers[Yii::$app->user->id] : null],
            'options' => ['class' => 'btn btn-info', 'onclick' => 'myModal($(this));return false;'],
            'symbol' => '&nbsp;',
            'conditions' => ($model->getIsStausShootIng() || $model->getIsAppraise()) && count($roleUsers) > 0 
                            && $model->date < date('Y-m-d', strtotime('+1 days')) && !$model->is_transfer,
            'adminOptions' => true,
        ],
    ];
    $btnGroup = '';
    foreach ($buttonHtml as $item){
        $btnGroup .= ResourceHelper::a($item['name'], $item['url'], $item['options'], $item['conditions']).($item['conditions'] ? $item['symbol'] : null);
    }
?>

<?php if(str_replace('&nbsp;', '', $btnGroup) != null): ?>

<div class="controlbar">
    <div class="container">
        <div class="footer-view-btn">
            <?= $btnGroup ?>
        </div>
    </div>
</div>

<?php endif; ?>
