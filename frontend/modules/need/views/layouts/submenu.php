<?php

use common\models\need\NeedTask;
use frontend\modules\need\assets\MainAssets;
use wskeee\rbac\components\ResourceHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

MainAssets::register($this);
?>

<div class="controlbar">
    <div class="submenu">
        <div class="container">
            <?php
                /**
                * $menuItems = [
                *   [
                *      controller => 控制器,                   
                *      action => 行为,                   
                *      name  => 菜单名称，
                *      number => 数量提醒
                *      url  =>  菜单url，
                *      icon => 菜单图标
                *      options  => 菜单属性，
                *      symbol => html字符符号：&nbsp;，
                *      conditions  => 菜单显示条件，
                *      adminOptions  => 菜单管理选项，
                *   ],
                * ]
                */
                $number = count(NeedTask::findAll(['is_del' => 0, 'status' => NeedTask::STATUS_WAITRECEIVE]));
                $controllerId = Yii::$app->controller->id;  //当前控制器
                $actionId = Yii::$app->controller->action->id;  //当前行为方法
                $menuItems = [
                    [
                        'controller' => 'default',
                        'action' => 'index',
                        'name' => '主页',
                        'number' => null,
                        'url' => ['default/index'],
                        'icon' => '<i class="glyphicon glyphicon-home"></i>',
                        'options' => ['class' => 'menu'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controller' => 'task',
                        'action' => 'index',
                        'name' => '列表',
                        'number' => null,
                        'url' => ['task/index'],
                        'icon' => '<i class="glyphicon glyphicon-th-list"></i>',
                        'options' => ['class' => 'menu'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controller' => 'task',
                        'action' => 'list',
                        'name' => '承接',
                        'url' => ['task/list'],
                        'number' => $number > 99 ? '99+' : $number,
                        'icon' => '<i class="glyphicon glyphicon-tasks"></i>',
                        'options' => ['class' => 'menu'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controller' => 'statistics',
                        'action' => ['cost', 'bonus', 'course-details', 'personal-details'],
                        'name' => '统计',
                        'number' => null,
                        'url' => ['statistics/cost'],
                        'icon' => '<i class="glyphicon glyphicon-stats"></i>',
                        'options' => ['class' => 'menu'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controller'=> ['business','college','project','course','expert', 'basedata-import'],
                        'action' => ['index', 'upload'],
                        'name' => '数据',
                        'number' => null,
                        'url' => ['business/index'],
                        'icon' => '<i class="glyphicon glyphicon-briefcase"></i>',
                        'options' => ['class' => 'menu'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controller'=> 'workitem',
                        'action' => 'index',
                        'name' => '样例',
                        'number' => null,
                        'url' => ['workitem/index'],
                        'icon' => '<i class="glyphicon glyphicon-th-large"></i>',
                        'options' => ['class' => 'menu'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controller' => 'task',
                        'action' => 'create',
                        'name' => '创建任务',
                        'number' => null,
                        'url' => ['task/create'],
                        'icon' => '<i class="glyphicon glyphicon-edit"></i>',
                        'options' => ['class' => 'menu right', 'target' => '_blank',],
                        'symbol' => '&nbsp;',
                        'conditions' => $controllerId === 'task' ? true : false,
                        'adminOptions' => null,
                    ],
                ];

                foreach ($menuItems AS $item){
                    $selected = is_array($item['controller']) ? in_array($controllerId, $item['controller']) : $controllerId == $item['controller'] && (is_array($item['action']) ? in_array($actionId, $item['action']) : $actionId == $item['action']);
                    $item['options']['class'] .= $selected ? ' active' : null;
                    echo ResourceHelper::a($item['icon'].Html::tag('span', $item['name'], ['class'=>'name hidden-xs']).Html::tag('span', $item['number'], ['class'=>'number'.(empty($item['number']) ? ' hidden' : '')]), $item['url'], 
                            $item['options'], $item['conditions']);
                }
            ?>
        </div>
    </div>
</div>