<?php

/* @var $this View */
/* @var $content string */

use common\config\AppGlobalVariables;
use common\models\System;
use frontend\assets\AppAsset;
use kartik\dropdown\DropdownX;
use kartik\widgets\AlertBlock;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

AppAsset::register($this);

$system = System::find()->orderBy('index asc')->with('jobs')->all();
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
        foreach ($system as $key => $value) {
            $menuItems[] = [
                'label' => $value->name, 
                'url' => 
                    $value->isjump == 0  ? [$value->module_link] : 
                    $value->module_link.'?userId='.Yii::$app->user->id.'&userName='.$user->username.'&timeStamp='.(time()*1000).'&sign='.strtoupper(md5($user->id.$user->username.(time()*1000).'eeent888888rms999999')),
                'linkOptions' => [
                    'target'=> $value->isjump == 0 ? '': "_black",
                    'title' => $value->module_link != '#' ? $value->name : '即将上线',
                ]
            ];
        }
    }
    
    $moduleId = Yii::$app->controller->module->id;   //模块ID
    if($moduleId == 'app-frontend')
    {
        //站点经过首页或登录，直接获取当前路由
        $route = Yii::$app->controller->getRoute();
    }else
    {
        /* 通过模块名拿到对应模块路由 */
        $urls = ArrayHelper::getColumn($menuItems, 'url');
        foreach($urls AS $url){
            if(stripos($url[0], $moduleId))
            {
                $route = substr($url[0], 1);
                break;
            }
        }
    }
    //控制器唯一ID
    $bar_route = Yii::$app->controller->getUniqueId();
    echo Nav::widget([
        'options' => Yii::$app->user->isGuest ? ['class' =>'navbar-nav navbar-right'] : ['class' => 'navbar-nav navbar-left'],
        'items' => $menuItems,
        'route' => $route,
    ]);
    
    /* 非站点记录系统别名和系统ID */
    if($bar_route != 'site'){
        AppGlobalVariables::$system_aliases = $moduleId;
        $system_id = System::find()->where(['aliases'=>AppGlobalVariables::$system_aliases])->one();
        AppGlobalVariables::$system_id = $system_id->id;
    }

    
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
