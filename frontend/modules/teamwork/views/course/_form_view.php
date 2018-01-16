<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\rbac\components\ResourceHelper;
use wskeee\rbac\RbacManager;

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
                    'url' => ['index'],//Yii::$app->request->getReferrer(),//获取上一次访问的链接
                    'options' => [
                        'class' => 'btn btn-default', 
                        /*'onclick'=> strpos(Yii::$app->request->getReferrer(), '/teamwork/course/index') === false ? 
                            'window.history.go(-2);return false' : 'window.history.go(-1);return false'*/],
                    'symbol' => '&nbsp;',
                    'conditions' => true,
                    'adminOptions' => true,
                ],
                //开发负责人和管理员角色按钮组
                [
                    'name' => '编辑',
                    'url' => ['update', 'id' => $model->id],
                    'options' => ['class' => 'btn btn-primary'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->getIsNormal(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '配置',
                    'url' => ['courselink/index', 'course_id' => $model->id],
                    'options' => ['class' => 'btn btn-success'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->getIsNormal(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '开始',
                    'url' => ['wait-start', 'id' => $model->id],
                    'options' => ['class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->getIsWaitStart(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '暂停',
                    'url' => ['pause', 'id' => $model->id],
                    'options' => ['id' => 'pause','class' => 'btn btn-info'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->getIsNormal(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '完成',
                    'url' => ['carry-out', 'id' => $model->id],
                    'options' => ['id' => 'carry-out','class' => 'btn btn-info'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->getIsNormal(),
                    'adminOptions' => true,
                ],
                [
                    'name' => '恢复',
                    'url' => ['normal', 'id' => $model->id],
                    'options' => ['id' => 'pause','class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => $model->getIsPause(),
                    'adminOptions' => true,
                ],
                //管理员角色按钮组
                [
                    'name' => '移交',
                    'url' => ['change', 'id' => $model->id],
                    'options' => ['id' => 'change','class' => 'btn btn-danger'],
                    'symbol' => '&nbsp;',
                    'conditions' => true,
                    'adminOptions' => true,
                ],
                [
                    'name' => '进度',
                    'url' => ['courselink/progress', 'course_id' => $model->id],
                    'options' => ['class' => 'btn btn-primary'],
                    'symbol' => '&nbsp;',
                    'conditions' => true,
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