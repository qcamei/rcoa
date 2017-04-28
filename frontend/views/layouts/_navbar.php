<?php

use common\config\AppGlobalVariables;
use common\models\System;
use frontend\assets\AppAsset;
use kartik\dropdown\DropdownX;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
AppAsset::register($this);
$moduleId = Yii::$app->controller->module->id;   //模块ID
$system = AppGlobalVariables::getSystems();

NavBar::begin([
        //'brandLabel' => '课程中心工作平台',
        'brandLabel' => Html::img(['/filedata/site/image/logo.png'], [
            'class' => 'logo'
        ]),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = [
            'label' => '登录', 
            'url' => ['/site/login'], 
        ];
    } else {
        $systems = AppGlobalVariables::__getSystems();
        foreach ($systems as $_system)
            $menuItems[] = $_system;
        
    }
    if($moduleId == 'app-frontend')
    {
        //站点经过首页或登录，直接获取当前路由
        $route = Yii::$app->controller->getRoute();
    }else
    {
        /* 通过模块名拿到对应模块路由 $urls = [aliases => [url],,,] */
        $urls = ArrayHelper::map($menuItems, 'aliases', 'url');
       
        $item = [];
        foreach ($menuItems as $items){
            if(isset($items['items'])){
                foreach ($items['items'] as $vals)
                    $urls[$vals['aliases']] = ArrayHelper::getValue($vals, 'url');
            } 
        }
        $route = substr($urls[$moduleId][0], 1);
    }
    echo Nav::widget([
        'options' => Yii::$app->user->isGuest ? ['class' =>'navbar-nav navbar-right'] : ['class' => 'navbar-nav navbar-left'],
        'items' => $menuItems,
        'route' => $route,
        'activateParents' => true,  //启用选择【子级】【父级】显示高亮
    ]);
    
    if(!Yii::$app->user->isGuest){
        echo Html::beginTag('ul', ['class'=>'navbar-nav navbar-right nav']);
        echo '<li class="dropdown">'.Html::a(Html::img('/filedata/image/u23.png',[
                'width'=>'20',
                'height'=>'20'
            ]), '', ['class'=>'dropdown-toggle', 'style'=>'height:50px', 'data-toggle'=>'dropdown'])
            .$this->render('_tasks_in', ['system' => $system]).'</li>';
        echo '<li class="dropdown">'.Html::a(Html::img('/filedata/image/u21.png',[
                'width'=>'20',
                'height'=>'20'
            ]), '', ['class'=>'dropdown-toggle', 'style'=>'height:50px', 'data-toggle'=>'dropdown'])
            .$this->render('_notification', ['system' => $system]).'</li>';
        echo '<li class="dropdown">'.Html::a(Html::img(Yii::$app->user->identity->avatar,[
            'width'=> '25', 
            'height' => '25',
            'style' => 'border: 1px solid #ccc;margin-top:-7px; margin-right:5px;',
            ]).Yii::$app->user->identity->nickname.'<b class="caret"></b>','',[
                'class'=>'dropdown-toggle',
                'data-toggle' => 'dropdown',
                'aria-expanded' => 'false',
            ]).DropdownX::widget([
                'options'=>['class'=>'dropdown-menu'], // for a right aligned dropdown menu
                'items' => [
                    ['label' => '我的属性', 'url' => '/site/reset-info', 'linkOptions'=>['class'=>'glyphicon glyphicon-user','style'=>'padding-left:5px;']],
                    ['label' => '登出', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post','class'=>'glyphicon glyphicon-log-out','style'=>'padding-left:5px;']],
                ],
            ]).'</li>'; 
        echo Html::endTag('ul');
    }
    
    NavBar::end();
?>