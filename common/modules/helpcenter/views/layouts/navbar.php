<?php

use common\modules\helpcenter\assets\HelpCenterAssets;
use kartik\dropdown\DropdownX;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
?>
<?php

NavBar::begin([
    'brandLabel' => Html::img(WEB_ROOT . '/filedata/site/image/icon_logo.png', ['class' => 'logo']),
    'brandUrl' => null,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
} else {
    $menuItems = [
        ['label' => '课程建设', 'url' => ['/helpcenter/default/index', 'app_id' => 'app-frontend']],
        ['label' => '课程制作', 'url' => ['/helpcenter/default/index', 'app_id' => 'app-mconline']],
    ];
//        $menuItems[] = [
//            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
//            'url' => ['/site/logout'],
//            'options' => ['class'=>'navbar-right'],
//            'linkOptions' => ['data-method' => 'post'],
//        ];
}

echo Nav::widget([
    'options' => Yii::$app->user->isGuest ? ['class' => 'navbar-nav navbar-right'] : ['class' => 'navbar-nav navbar-left', 'style' => 'width:75%'],
    'items' => $menuItems,
]);
if (!Yii::$app->user->isGuest) {
    echo "<ul class=\"navbar-nav navbar-right nav\">" .
    "<li class=\"dropdown\">" .
    Html::a(Html::img(WEB_ROOT . Yii::$app->user->identity->avatar, ['width' => '25', 'height' => '25',
                'style' => 'border: 1px solid #ccc;margin-top:-7px; margin-right:5px;',
            ]) . Yii::$app->user->identity->nickname . "<b class=\"caret\"></b>", 'javascript:;', [
        'class' => 'dropdown-toggle',
        'data-toggle' => 'dropdown',
        'aria-expanded' => 'false']) . DropdownX::widget([
        'options' => ['class' => 'dropdown-menu'], // for a right aligned dropdown menu
        'items' => [
            ['label' => '我的属性', 'url' => ['/site/info'], 'linkOptions' => ['class' => 'glyphicon glyphicon-user', 'style' => 'padding-left:5px;']],
            ['label' => Yii::t('app', 'Login Out'), 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post', 'class' => 'glyphicon glyphicon-log-out', 'style' => 'padding-left:5px;']],
        ],
    ]) .
    "</li>" .
    "</ul>";
}

NavBar::end();
?>
<?php
    HelpCenterAssets::register($this);
