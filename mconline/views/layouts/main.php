<?php

/* @var $this View */
/* @var $content string */

use backend\assets\AppAsset;
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
        'brandLabel' => '在线制作课程平台',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    $menuItems = [
        ['label' => '首页', 'url' => ['/site/index']],
    ];
    
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems = [
            ['label' => '首页', 'url' => ['/site/index']],
            ['label' => '板书课堂', 'url' => ['/mcbs/default/index']],
            ['label' => '情景课堂', 'url' => ['#']],
            ['label' => '帮助中心', 'url' => ['/helpcenter/default/index']],
        ];
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'options' => ['class'=>'navbar-right'],
            'linkOptions' => ['data-method' => 'post'],
        ];
    }
    echo Nav::widget([
        'options' => Yii::$app->user->isGuest ? ['class' =>'navbar-nav navbar-right'] : ['class' => 'navbar-nav navbar-left','style'=>'width:86%'],
        'items' => $menuItems,
    ]);
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
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
