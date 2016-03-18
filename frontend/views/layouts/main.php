<?php

/* @var $this View */
/* @var $content string */

use common\models\System;
use common\models\User;
use frontend\assets\AppAsset;
use kartik\dropdown\DropdownX;
use kartik\widgets\AlertBlock;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\web\View;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '课程中心工作平台',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [['label' => '首页','url' => ['/site/index'],],];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = [
            'label' => '登录', 
            'url' => ['/site/login'], 
        ];
    } else {
        $system = System::find()->all();
        $user = User::findOne(Yii::$app->user->id);
        foreach ($system as $key => $value) {
            $menuItems[] = [
                'label' => $value->name, 
                'url' => 
                    $value->isjump == 0  ? [$value->module_link] : 
                    $value->module_link.'?userId='.$user->id.'&userName='.$user->username.'&timeStamp='.(time()*1000).'&sign='.strtoupper(md5($user->id.$user->username.(time()*1000).'eeent888888rms999999')),
                'linkOptions' => [
                    'target'=> $value->isjump == 0 ? '': "_black",
                    'title' => $value->module_link != '#' ? $value->name : '即将上线',
                ]
            ];
        }
       
        
    }
    
    $bar_route = Yii::$app->controller->getUniqueId();
    echo Nav::widget([
        'options' => Yii::$app->user->isGuest ? ['class' =>'navbar-nav navbar-right'] : ['class' => 'navbar-nav navbar-left'],
        'items' => $menuItems,
        'route' => $bar_route == 'site' ? Yii::$app->controller->getRoute() : $bar_route,
    ]);
    if(!Yii::$app->user->isGuest){
        echo Html::beginTag('ul', ['class'=>'navbar-nav navbar-right nav']);
        echo '<li class="dropdown">'.Html::a(Html::img(Yii::$app->user->identity->avatar,[
            'width'=> '30', 
            'height' => '30',
            'style' => 'border: 1px solid #ccc;margin-top:-13px; margin-right:5px;',
            ]).Yii::$app->user->identity->nickname.'<b class="caret"></b>','',[
                'class'=>'dropdown-toggle',
                'data-toggle' => 'dropdown',
                'aria-expanded' => 'false',
            ]).DropdownX::widget([
                'options'=>['class'=>'dropdown-menu'], // for a right aligned dropdown menu
                'items' => [
                    ['label' => '我的属性', 'url' => '/site/reset-info'],
                    ['label' => '登出', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
                ],
            ]).'</li>'; 
        echo Html::endTag('ul');
    }
    NavBar::end();
    ?>
    <div class="content">
        <?php
            echo AlertBlock::widget([
                'useSessionFlash' => TRUE,
                'type' => AlertBlock::TYPE_GROWL,
                'delay' => 0
            ]);
        ?>
        <?= $content ?>
    </div>
    <!--
    
    <div class="container">
        
    </div>
    -->
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; 广州远程教育中心有限公司 </p>

       
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
