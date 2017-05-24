<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

/* @var $model CourseManage */
/* @var $twTool TeamworkTool */ 
/* @var $rbacManager RbacManager */  

$page = [
    'index', 
    'team_id' => $model->team_id,
    'status' => CourseManage::STATUS_NORMAL
];
?>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), $page, ['class' => 'btn btn-default', /*'onclick'=> 'history.go(-1)'*/]) ?>
        <?php
            if($model->coursePrincipal->u_id == Yii::$app->user->id || $rbacManager->isRole(RbacName::ROLE_TEAMWORK_DEVELOP_MANAGER, Yii::$app->user->id))
            {        
                /**
                 * 编辑 按钮显示必须满足以下条件：
                 * 1、必须是状态为【在建中】
                 * 2、必须是拥有编辑权限
                 * 3、创建者是自己
                 * 4、课程负责人是自己
                 * 5、必须是【开发管理员】
                 */
                if($model->getIsNormal()) 
                    echo Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
                /**
                 * 配置 按钮显示必须满足以下条件：
                 * 1、必须是状态为【在建中】
                 * 2、必须是拥有配置权限
                 * 3、创建者是自己
                 * 4、课程负责人是自己
                 * 5、必须是【开发管理员】
                 */
                if($model->getIsNormal())    
                    echo Html::a('配置', ['/teamwork/courselink/index', 'course_id' => $model->id], ['class' => 'btn btn-success']).' ';
                /**
                 * 开始 按钮显示必须满足以下条件：
                 * 1、必须是状态为【待开始】
                 * 2、必须拥有开始权限
                 * 3、创建者是自己
                 * 4、课程负责人是自己
                 * 5、必须是【开发管理员】
                 */
                if($model->getIsWaitStart())
                    echo Html::a('开始', ['wait-start', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
                /**
                 * 完成 按钮显示必须满足以下条件：
                 * 1、必须是状态为【在建中】
                 * 2、必须拥有完成权限
                 * 3、创建者是自己
                 * 4、课程负责人是自己
                 * 5、必须是【开发管理员】
                 */
                if($model->getIsNormal())
                    echo Html::a('暂停', ['pause', 'id' => $model->id], ['id' => 'pause', 'class' => 'btn btn-info']).' ';
                
                /**
                 * 完成 按钮显示必须满足以下条件：
                 * 1、必须是状态为【在建中】
                 * 2、必须拥有完成权限
                 * 3、创建者是自己
                 * 4、课程负责人是自己
                 * 5、必须是【开发管理员】
                 */
                if($model->getIsNormal())
                    echo Html::a('完成', ['carry-out', 'id' => $model->id], ['id' => 'carry-out', 'class' => 'btn btn-danger']).' ';
                
                 /**
                 * 恢复 按钮显示必须满足以下条件：
                 * 1、必须是状态为【暂停中】
                 * 2、必须拥有恢复权限
                 * 3、必须是 【课程负责人是自己】 或 【开发管理员】
                 */
                if($model->getIsPause())
                    echo Html::a('恢复', ['normal', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
            }
            
            echo Html::a('进度', ['courselink/progress', 'course_id' => $model->id], ['class' => 'btn btn-primary']).' ';
            
            if($rbacManager->isRole(RbacName::ROLE_TEAMWORK_DEVELOP_MANAGER, Yii::$app->user->id)){
                /**
                 * 移交 按钮显示必须满足以下条件：
                 * 1、必须是状态为【在建中】
                 * 2、必须拥有移交权限
                 * 3、必须是【开发管理员】
                 */
                if($rbacManager->isRole(RbacName::ROLE_TEAMWORK_DEVELOP_MANAGER, Yii::$app->user->id) && $model->getIsNormal())
                    echo Html::a('移交', ['change', 'id' => $model->id], ['id' => 'change', 'class' => 'btn btn-danger']).' ';

                /**
                 * 恢复 按钮显示必须满足以下条件：
                 * 1、必须是状态为【已完成】
                 * 2、必须拥有恢复权限
                 * 3、必须是 【开发管理员】
                 */
                if($model->getIsCarryOut())
                    echo Html::a('恢复', ['normal', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
            }                
            
        ?>
    </div>
</div>
