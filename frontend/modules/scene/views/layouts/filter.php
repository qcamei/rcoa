<?php

use frontend\modules\scene\assets\SceneAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

?>

<div id="filter" class="filter">
    <?php
    /**
     * $menuItems = [
     *   [
     *      name  => 菜单名称，
     *      url  =>  菜单url，
     *      icon => 菜单图标
     *      options  => 菜单属性，
     *      symbol => html字符符号：&nbsp;，
     *      conditions  => 菜单显示条件，
     *   ],
     * ]
     */
    $actionId = Yii::$app->controller->action->id;      //当前行为方法
    $requestUrl = Yii::$app->request->url;
    $menuItems = [
        [
            'actionId' => 'default',
            'name' => '默认',
            'url' => array_merge([$actionId], array_merge($filter, ['sort_order' => 'sort_order', '#' => 'scroll'])),
            'icon' => null,
            'options' => ['class' => 'active'],
            'symbol' => '&nbsp;',
            'conditions' => true,
        ],
        [
            'actionId' => 'price',
            'name' => '价格',
            'url' => array_merge([$actionId], array_merge($filter, ['sort_order' => 'price_order', '#' => 'scroll'])),
            'icon' => null,
            'options' => ['class' => 'active'],
            'symbol' => '&nbsp;',
            'conditions' => true,
        ],
    ];

    foreach ($menuItems as $index => $item) {
        $active = $requestUrl == str_replace('#scroll', '', Url::to($item['url'])) || (!isset($filter['sort_order']) && $index == 0) ? $item['options'] : [];
        if ($item['conditions']) {
            echo '<div class="sort-order">' .
            Html::a($item['icon'] . $item['name'], $item['url'], $active)
            . '</div>';
        }
    }
    ?>

</div>
<?php
$js = <<<JS
 
JS;
//$this->registerJs($js, View::POS_READY);
?>

<?php
    SceneAsset::register($this);
