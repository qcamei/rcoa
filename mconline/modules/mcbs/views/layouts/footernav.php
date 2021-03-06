<?php

use mconline\modules\mcbs\assets\McbsAssets;
use wskeee\rbac\components\ResourceHelper;
?>

<div class="hidden-lg footer-nav ">

    <div class="navigation col-xs-12">
        <?php
        /**
         * $menuItems = [
         *   [
         *      controllerId => 控制器ID,                          
         *      actionId => 行为方法ID,                          
         *      name  => 菜单名称，
         *      url  =>  菜单url，
         *      icon => 菜单图标
         *      options  => 菜单属性，
         *      symbol =>  html字符符号：&nbsp;，等
         *      conditions  => 菜单显示条件，
         *      adminOptions  => 菜单管理选项，
         *   ],
         * ]
         */
        $actionId = Yii::$app->controller->action->id;      //当前行为方法
        $selectClass = 'active';                            //选择样式
        $menuItems = [
            [
                'actionId' => 'create',
                'name' => '创建课程',
                'url' => ['create'],
                'icon' => '<i class="fa fa-pencil-square-o"></i>',
                'options' => ['class' => null],
                'symbol' => null,
                'conditions' => true,
                'adminOptions' => null,
            ],
            [
                'actionId' => 'index',
                'name' => '我的课程',
                'url' => ['index'],
                'icon' => '<i class="fa fa-home"></i>',
                'options' => ['class' => null],
                'symbol' => null,
                'conditions' => true,
                'adminOptions' => null,
            ],
            [
                'actionId' => 'attention-index',
                'name' => '我的关注',
                'url' => ['attention-index'],
                'icon' => '<i class="fa fa-star"></i>',
                'options' => ['class' => null],
                'symbol' => null,
                'conditions' => true,
                'adminOptions' => null,
            ],
            [
                'actionId' => 'lookup-index',
                'name' => '查找课程',
                'url' => ['lookup-index'],
                'icon' => '<i class="fa fa-search"></i>',
                'options' => ['class' => null],
                'symbol' => null,
                'conditions' => true,
                'adminOptions' => null,
            ],
        ];

        foreach ($menuItems AS $item) {
            $selected = is_array($item['actionId']) ? in_array($actionId, $item['actionId']) : $actionId == $item['actionId'];
            $active = $selected ? $selectClass : null;
            echo "<div class=\"{$active} footer-menu-item col-xs-3\">";
            echo ResourceHelper::a($item['icon'], $item['url']);
            echo '</div>';
        }
        ?>

    </div>
</div>

<?php
McbsAssets::register($this);
?>
