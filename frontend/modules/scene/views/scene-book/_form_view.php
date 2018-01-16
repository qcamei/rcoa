<?php

use common\models\scene\SceneBook;
use wskeee\rbac\RbacManager;
use yii\helpers\Html;

/* @var $model SceneBook */
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
                        'options' => ['class' => 'btn btn-default', 'onclick'=> 'history.go(-1);return false'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => true,
                    ],
                    //预约人角色按钮组
                    [
                        'name' => '编辑',
                        'url' => ['update', 'id' => $model->id],
                        'options' => ['class' => 'btn btn-primary'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => true,
                    ],
                    [
                        'name' => '申请转让',
                        'url' => ['shift', 'book_id' => $model->id],
                        'options' => ['id' => '', 'class' => 'btn btn-danger'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => true,
                    ],
                    //摄影组长角色按钮组
                    [
                        'name' => '指派',
                        'url' => ['assign', 'id' => $model->id],
                        'options' => ['class' => 'btn btn-primary', 'onclick' => 'myModal($(this));return false;'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => true,
                    ],
                    //接洽人和摄影师角色按钮
                    [
                        'name' => '评价',
                        'url' => ['appraise/create', 'book_id' => $model->id, 'role' => $isRole['role'], 'user_id' => $isRole['user_id']],
                        'options' => ['class' => 'btn btn-info', 'onclick' => 'myModal($(this));return false;'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => true,
                    ],
                ];

                foreach ($buttonHtml as $item){
                    //echo ResourceHelper::a($item['name'], $item['url'], $item['options'], $item['conditions']).($item['conditions'] ? $item['symbol'] : null);
                    echo Html::a($item['name'], $item['url'], $item['options']).($item['conditions'] ? $item['symbol'] : null);
                }

            ?>
        </div>
    </div>
</div>