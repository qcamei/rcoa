<?php

use frontend\modules\demand\assets\DemandAssets;
use wskeee\rbac\components\ResourceHelper;
use yii\helpers\Html;

?>

<div class="controlbar demand">
    <div class="footer-navbar">
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
                $controllerId = Yii::$app->controller->id;          //当前控制器
                $actionId = Yii::$app->controller->action->id;      //当前行为方法
                $selectClass = ' footer-menu-bg';                   //选择样式
                $menuItems = [
                    [
                        'controllerId' => 'default',
                        'name' => '主页',
                        'url' => ['default/index'],
                        'icon' => '/filedata/demand/image/home.png',
                        'options' => ['class' => 'footer-menu-item'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controllerId' => 'task',
                        'name' => '课程',
                        'url' => ['task/index'],
                        'icon' => '/filedata/demand/image/list-check.png',
                        'options' => ['class' => 'footer-menu-item'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controllerId' => 'statistics',
                        'name' => '统计',
                        'url' => ['statistics/index'],
                        'icon' => '/filedata/demand/image/statistics.png',
                        'options' => ['class' => 'footer-menu-item'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controllerId'=> ['business','college','project','course','expert'],
                        'name' => '数据',
                        'url' => ['business/index'],
                        'icon' => '/filedata/demand/image/data_configuration_64.png',
                        'options' => ['class' => 'footer-menu-item'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controllerId'=> 'workitem',
                        'name' => '样例',
                        'url' => ['workitem/list'],
                        'icon' => '/filedata/demand/image/yangliku.png',
                        'options' => ['class' => 'footer-menu-item'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
                        'adminOptions' => null,
                    ],
                    [
                        'controllerId' => 'task',
                        'name' => '创建任务',
                        'url' => ['task/create'],
                        'icon' => '/filedata/demand/image/create.png',
                        'options' => ['class' => 'footer-menu-item submenu-right'],
                        'symbol' => '&nbsp;',
                        'conditions' => $controllerId == 'task' ? true : false,
                        'adminOptions' => null,
                    ],
                ];

                foreach ($menuItems AS $item){
                    $selected = is_array($item['controllerId']) ? in_array($controllerId, $item['controllerId']) : $controllerId == $item['controllerId'];
                    $item['options']['class'] .= $selected ? $selectClass : null;
                    echo ResourceHelper::a(Html::img([$item['icon']]).Html::tag('span', $item['name'], ['class'=>'menu-name hidden-xs']), $item['url'], 
                            $item['options'], $item['conditions']);
                }
               
            ?>
        </div>
    </div>
</div>

<?php
    DemandAssets::register($this);
?>
