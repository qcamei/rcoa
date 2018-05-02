<?php

use common\modules\helpcenter\controllers\DefaultController;
use common\widgets\Menu;

$menus = DefaultController::getMenu($app_id);

?>
<aside class="main-sidebar">
    <section class="sidebar">
        <?php
            $menuItems = [
                ['label' => '目录', 'options' => ['class' => 'header']],
            ];
            foreach ($menus as $items) {
                $menuItems[] = $items;
            }
            echo Menu::widget(
                    [
                        'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                        'items' => $menuItems
                    ]
            )
        ?>
    </section>
</aside>