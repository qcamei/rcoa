<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\rbac\RbacName;
use yii\helpers\Html;

/* @var $model CourseManage */
/* @var $twTool TeamworkTool */ 

$page = [
    'index', 
    'create_by' => Yii::$app->user->id,
    'status' => CourseManage::STATUS_NORMAL
];
?>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), $page, ['class' => 'btn btn-default', /*'onclick'=>'history.go(-1)'*/]) ?>
        <?php
            if(($twTool->getIsAuthority('is_leader', 'Y') && $model->create_by == Yii::$app->user->id)
                || $twTool->getIsAuthority('id', $model->course_principal) || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER))
            {
                /**
                 * 编辑 按钮显示必须满足以下条件：
                 * 1、状态非为【已完成】
                 * 2、必须是【队长】
                 * 3、创建者是自己
                 * 4、课程负责人是自己
                 * 5、必须是项目管理员
                 */
                if(!$model->getIsCarryOut())
                    echo Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
                /**
                 * 配置 按钮显示必须满足以下条件：
                 * 1、状态非为【已完成】
                 * 2、必须是【队长】
                 * 3、创建者是自己
                 * 4、课程负责人是自己
                 * 5、必须是项目管理员
                 */
                if(!$model->getIsCarryOut())    
                    echo Html::a('配置', ['/teamwork/courselink/index', 'course_id' => $model->id], ['class' => 'btn btn-success']).' ';

                /**
                 * 完成 按钮显示必须满足以下条件：
                 * 1、必须是状态为【在建中】
                 * 2、必须是【队长】
                 * 3、创建者是自己
                 * 4、课程负责人是自己
                 * 5、必须是项目管理员
                 */
                if($model->getIsNormal())
                    echo Html::a('完成', ['carry-out', 'id' => $model->id], ['id' => 'carry-out', 'class' => 'btn btn-danger']).' ';

                /**
                 * 开始 按钮显示必须满足以下条件：
                 * 1、必须是状态为【待开始】
                 * 2、必须是【队长】
                 * 3、创建者是自己
                 * 4、课程负责人是自己
                 * 5、必须是【项目管理员】
                 */
                if($model->getIsWaitStart())
                    echo Html::a('开始', ['wait-start', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
            }
            
            echo Html::a('进度', ['courselink/progress', 'course_id' => $model->id], ['class' => 'btn btn-primary']).' ';
            
            /**
             * 移交 按钮显示必须满足以下条件：
             * 1、状态非为【已完成】
             * 2、必须是【项目管理员】
             */
            if(!$model->getIsCarryOut() && Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER))
                echo Html::a('移交', ['change', 'id' => $model->id], ['id' => 'change', 'class' => 'btn btn-danger']).' ';
            
            /**
             * 恢复 按钮显示必须满足以下条件：
             * 1、必须是状态为【已完成】
             * 2、必须是【项目管理员】
             */
            if($model->getIsCarryOut() && Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER))
                echo Html::a('恢复', ['normal', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
        ?>
    </div>
</div>
