<?php

use mconline\modules\mcbs\assets\FooterAsset;
use wskeee\rbac\components\ResourceHelper;
use yii\helpers\Html;

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
        'name' => '板书制作',
        'url' => 'CourseMaker.Mconline://open',
        'icon' => '/upload/mcbs/images/mcbs-tool-icon.png',
        'options' => ['class' => 'footer-menu-item'],
        'symbol' => '&nbsp;',
        'conditions' => true,
        'adminOptions' => null,
    ],
    [
        'controllerId' => 'task',
        'name' => '情景制作',
        'url' => ['#'],
        'icon' => '/upload/mcbs/images/mcqj-tool-icon.png',
        'options' => ['class' => 'footer-menu-item'],
        'symbol' => '&nbsp;',
        'conditions' => true,
        'adminOptions' => null,
    ],
];

?>

<div class="mcbs-view">
    <div class="footer-navbar">
        <div class="container">
            <span style="font-size: 18px;line-height: 50px;">请选择课件制作工具</span>
            <?php
            foreach ($menuItems AS $item) {
                echo Html::a(Html::img([$item['icon']]) . Html::tag('span', $item['name'], ['class' => 'menu-name hidden-xs']), $item['url'], $item['options']);
                if ($item["controllerId"] == "default") {
                    echo '<div class="footer-h-rule"> </div>';
                }
            }
            ?>
        </div>
    </div>
</div>

<?php
FooterAsset::register($this);
?>
