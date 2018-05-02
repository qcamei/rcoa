<?php

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
                *      controllerId => 控制器ID,                          
                *      name  => 菜单名称，
                *      url  =>  菜单url，
                *      icon => 菜单图标
                *      options  => 菜单属性，
                *      symbol => html字符符号：&nbsp;，
                *      conditions  => 菜单显示条件，
                *      adminOptions  => 菜单管理选项，
                *   ],
                * ]
                */
                $controllerId = Yii::$app->controller->id;  //当前控制器
                $actionId = Yii::$app->controller->action->id;  //当前行为方法
                $menuItems = [
                    [
                        'controllerId' => 'default',
                        'name' => '主页',
                        'url' => ['default/index'],
                        'icon' => '<i class="glyphicon glyphicon-home"></i>',
                        'options' => ['class' => 'menu'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controllerId' => 'task',
                        'name' => '课程',
                        'url' => ['task/index'],
                        'icon' => '<i class="glyphicon glyphicon-list"></i>',
                        'options' => ['class' => 'menu'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controllerId' => 'statistics',
                        'name' => '统计',
                        'url' => ['statistics/cost'],
                        'icon' => '<i class="glyphicon glyphicon-stats"></i>',
                        'options' => ['class' => 'menu'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controllerId'=> ['business','college','project','course','expert'],
                        'name' => '数据',
                        'url' => ['business/index'],
                        'icon' => '<i class="glyphicon glyphicon-briefcase"></i>',
                        'options' => ['class' => 'menu'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controllerId'=> 'workitem',
                        'name' => '样例',
                        'url' => ['workitem/index'],
                        'icon' => '<i class="glyphicon glyphicon-tasks"></i>',
                        'options' => ['class' => 'menu'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controllerId' => 'task',
                        'name' => '创建任务',
                        'url' => ['task/create'],
                        'icon' => '<i class="glyphicon glyphicon-edit"></i>',
                        'options' => ['class' => 'menu right'],
                        'symbol' => '&nbsp;',
                        'conditions' => $controllerId === 'task' ? true : false,
                        'adminOptions' => null,
                    ],
                ];

                foreach ($menuItems AS $item){
                    $selected = is_array($item['controllerId']) ? in_array($controllerId, $item['controllerId']) : $controllerId === $item['controllerId'];
                    $item['options']['class'] .= $selected ? ' active' : null;
                    echo ResourceHelper::a($item['icon'].Html::tag('span', $item['name'], ['class'=>'name hidden-xs']), $item['url'], 
                            $item['options'], $item['conditions']);
                }
            ?>
        </div>
    </div>
</div>