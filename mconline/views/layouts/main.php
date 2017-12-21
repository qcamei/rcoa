<?php

/* @var $this View */
/* @var $content string */

use backend\assets\AppAsset;
use kartik\dropdown\DropdownX;
use kartik\widgets\AlertBlock;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

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
        'brandLabel' => Html::img(WEB_ROOT.'/filedata/site/image/icon_logo.png', ['class' => 'logo']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    $menuItems = [
        ['label' => '首页', 'url' => ['/site/index']],
    ];
    
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']];
    } else {
        $menuItems = [
            ['label' => '首页', 'url' => ['/site/index']],
            ['label' => '板书课堂', 'url' => ['/mcbs/default/index']],
            ['label' => '情景课堂', 'url' => 'javascript:;'],
            [
                'label' => '帮助中心',
                'url' => ['/helpcenter/default/index', 'app_id'=> 'app-mconline'],
                'linkOptions'=>['target'=>'_blank']
            ],
        ];
//        $menuItems[] = [
//            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
//            'url' => ['/site/logout'],
//            'options' => ['class'=>'navbar-right'],
//            'linkOptions' => ['data-method' => 'post'],
//        ];
    }
    
    echo Nav::widget([
        'options' => Yii::$app->user->isGuest ? ['class' =>'navbar-nav navbar-right'] : ['class' => 'navbar-nav navbar-left','style'=>'width:75%'],
        'items' => $menuItems,
    ]);
    if(!Yii::$app->user->isGuest){
        echo "<ul class=\"navbar-nav navbar-right nav\">".
            "<li class=\"dropdown\">".
                Html::a(Html::img(WEB_ROOT.Yii::$app->user->identity->avatar,['width'=> '25','height' => '25',
                    'style' => 'border: 1px solid #ccc;margin-top:-7px; margin-right:5px;',
               ]).Yii::$app->user->identity->nickname."<b class=\"caret\"></b>",'javascript:;',[
                'class'=>'dropdown-toggle',
                'data-toggle' => 'dropdown',
                'aria-expanded' => 'false']).DropdownX::widget([
                    'options'=>['class'=>'dropdown-menu'], // for a right aligned dropdown menu
                    'items' => [
                        ['label' => '我的属性', 'url' => ['/site/info'], 'linkOptions'=>['class'=>'glyphicon glyphicon-user','style'=>'padding-left:5px;']],
                        ['label' => Yii::t('app', 'Login Out'), 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post','class'=>'glyphicon glyphicon-log-out','style'=>'padding-left:5px;']],
                    ],
                ]).
            "</li>".
        "</ul>";
    }
    
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php  //Alert::widget() ?>
        <?php
            echo AlertBlock::widget([
                'useSessionFlash' => TRUE,
                'type' => AlertBlock::TYPE_GROWL,
                'delay' => 0
            ]);
        ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; 广州远程教育中心&nbsp;&nbsp;版本号：v1.50 </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
