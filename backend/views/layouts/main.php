<?php

/* @var $this View */
/* @var $content string */

use backend\assets\AppAsset;
use common\widgets\Alert;
use kartik\dropdown\DropdownX;
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
        'brandLabel' => 'RBAC',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        [
            'label' => '首页', 
            'items' => [
                ['label' => '新闻事件', 'url' => '/news/default'],
                ['label' => '宣传栏', 'url' => '/banner/default'],
            ]
        ]
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => '拍摄',
            'items' => [
                 ['label' => '评价题目', 'url' => '/shoot/appraise'],
                 ['label' => '场地管理', 'url' => '/shoot/site'],
            ]
        ];
        $menuItems[] = ['label' => '多媒体制作','url' => '#'];
        $menuItems[] = ['label' => '评优','url' => '#'];
        $menuItems[] = [
            'label' => '专家库',
            'items' => [
                 ['label' => '专家管理', 'url' => '/expert/default/'],
            ]
        ];
        $menuItems[] = [
            'label' => '用户',
            'items' => [
                 ['label' => '用户', 'url' => '/user'],
                 ['label' => '角色', 'url' => '/rbac/role'],
                 ['label' => '权限', 'url' => '/rbac/permission'],
                 ['label' => '规则', 'url' => '/rbac/rule'],
            ]
        ];
        $menuItems[] = [
            'label' => '项目',
            'items' => [
                ['label' => '学院', 'url' => '/framework/college'],
                ['label' => '项目', 'url' => '/framework/project'],
                ['label' => '课程', 'url' => '/framework/course'],
            ]
        ];
        $menuItems[] = [
            'label' => '题库',
            'items' => [
                ['label' => '题目管理', 'url' => '/question'],
            ]
        ];
        $menuItems[] = [
            'label' => '资源展示',
            'items' => [
                ['label' => '资源管理', 'url' => '/resource/default'],
            ],
        ];
        $menuItems[] = [
            'label' => '文档管理',
            'items' => [
                ['label' => '文档目录', 'url' => '/filemanage/default'],
                ['label' => '文档详情', 'url' => '/filemanage/detail'],
            ],
        ];
        $menuItems[] = [
            'label' => '团队管理',
            'url' => '/team/team',
        ];


    /*$menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
        
        //$menuItems[] = '<li><img class=".img-responsive"  src="'.FILEDATA_PATH.Yii::$app->user->identity->avatar.'" width="30" height="30"  ></li>';*/
    }
    echo Nav::widget([
        'options' =>Yii::$app->user->isGuest ? ['class' =>'navbar-nav navbar-right'] : ['class' => 'navbar-nav navbar-left'],
        'items' => $menuItems,
    ]);
    if(!Yii::$app->user->isGuest){
        echo Html::beginTag('ul', ['class'=>'navbar-nav navbar-right nav']);
        echo '<li class="dropdown">'.Html::a(Html::img(FILEDATA_PATH . Yii::$app->user->identity->avatar,[
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
                    //['label' => '我的属性', 'url' => '/site/reset-info'],
                    ['label' => '登出', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post','class'=>'glyphicon glyphicon-log-out','style'=>'padding-left:5px;']],
                ],
            ]).'</li>'; 
        echo Html::endTag('ul');
    }
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; 广州远程教育中心有限公司</p>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
