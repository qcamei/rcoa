<?php

use frontend\modules\scene\assets\SceneAsset;
use wskeee\rbac\components\ResourceHelper;
use yii\helpers\Html;

?>

<div class="controlbar scene">
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
                        'controllerId' => 'scene-book',
                        'name' => '预约',
                        'url' => ['scene-book/index'],
                        'icon' => '/filedata/demand/image/calendar.png',
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
                        'controllerId' => 'scene-manage',
                        'name' => '场地',
                        'url' => ['scene-manage/index'],
                        'icon' => '/filedata/demand/image/telescope.png',
                        'options' => ['class' => 'footer-menu-item'],
                        'symbol' => '&nbsp;',
                        'conditions' => true,
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
    SceneAsset::register($this);
