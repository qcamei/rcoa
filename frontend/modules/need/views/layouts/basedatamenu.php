<?php

use frontend\modules\need\assets\BasedatamenuAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $content string */

BasedatamenuAssets::register($this);

$this->title = Yii::t('app', 'Basedata');

?>

<?php
$menu = '';
$controllerId = Yii::$app->controller->id;
//导航
$menuItems = [
    [
        'label' => Yii::t('app', 'Item Type ID'),
        'url' => ['business/index'],
        'icons' => '', 
        'options' => ['class' => 'links']
    ],
    [
        'label' => Yii::t('app', 'Item ID'),
        'url' => ['college/index'],
        'icons' => '', 
        'options' => ['class' => 'links']
    ],
    [
        'label' => Yii::t('app', 'Item Child ID'),
        'url' => ['project/index'],
        'icons' => '', 
        'options' => ['class' => 'links']
    ],
    [
        'label' => Yii::t('app', 'Courses'),
        'url' => ['course/index'],
        'icons' => '', 
        'options' => ['class' => 'links']
    ],
    [
        'label' => Yii::t('app', 'Expert'),
        'url' => ['expert/index'],
        'icons' => '', 
        'options' => ['class' => 'links']
    ],
    [
        'label' => Yii::t('app', '{Import}{Basedata}',[
                'Import' => Yii::t('app', 'Import'),'Basedata' => Yii::t('app', 'Basedata'),]),
        'url' => ['basedata-import/upload'],
        'icons' => '', 
        'options' => ['class' => 'links']
    ],
];

//导航
end($menuItems);
$lastIndex = key($menuItems);
foreach ($menuItems as $index => $item) {
    $itemController = strstr($item['url'][0], '/', true);
    $menu .= ($controllerId ===  $itemController ? '<li class="active">' : 
            ($lastIndex == $index ? '<li class="remove">' : '<li class="">')).Html::a($item['label'], $item['url'], $item['options']).'</li>';
}

$html = <<<Html
    <div class="container">
        <div class="basedata-content">
            <nav class="subnav">
                <div class="menu">
                    <div class="title">
                        <i class="glyphicon glyphicon-th-list"></i>
                        <span>导航</span>
                    </div>
                    <ul>{$menu}</ul>
                </div>
            </nav>
Html;

    $content = $html.$content.'</div></div>' . $this->render('submenu');
    echo $this->render('@app/views/layouts/main',['content' => $content]); 
    
?>