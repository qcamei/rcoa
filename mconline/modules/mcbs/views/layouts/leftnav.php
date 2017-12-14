<?php

use mconline\modules\mcbs\assets\McbsAssets;
use wskeee\rbac\components\ResourceHelper;
use yii\helpers\Html;
?>

<div class="col-lg-2 visible-lg-block left-nav">
    <p> 
        <?=
            ResourceHelper::a(Yii::t('app', 'Create'), ['create'], [
                'class' => 'btn btn-success',
                'style' => ['width' => '120px','font-size' => '16px',]
            ])
        ?> 
    </p>

    <ul class="navigation">
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
         *      symbol =>  html字符符号：&nbsp;，
         *      conditions  => 菜单显示条件，
         *      adminOptions  => 菜单管理选项，
         *   ],
         * ]
         */
        $actionId = Yii::$app->controller->action->id;      //当前行为方法
        $selectClass = 'active';                            //选择样式
        $menuItems = [
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
            echo "<li class=\"{$active}\">";
            echo Html::a($item['icon'] . $item['name'], $item['url']);
            echo '</li>';
        }
        ?>

    </ul>
</div>

<?php
   McbsAssets::register($this);
?>