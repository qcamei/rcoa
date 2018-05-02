<?php

use frontend\modules\need\assets\MainAssets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

MainAssets::register($this);

?>

<?php
$menu = '';
$hostUrl = \Yii::$app->request->getPathInfo();  //获取url中的路径信息（不包含host和参数）
$url = trim(strrchr($hostUrl, '/'),'/');    //截取最后一个斜杠后面的内容;
//导航
$menus = [
   [
       'name'=>  Yii::t('app', 'Cost'),
       'url'=>['cost'],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  Yii::t('app', 'Bonus'),
       'url'=>['bonus'],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  Yii::t('app', 'Course Details'),
       'url'=>['course-details'],
       'class'=>'btn btn-default',
   ],
   [
       'name'=>  Yii::t('app', 'Personal Details'),
       'url'=>['personal-details'],
       'class'=>'btn btn-default',
   ],
];

foreach ($menus AS $index => $menuItem) {
    $active = $menuItem['url'][0] == $url ? ' active' : '';
    $menu .= Html::a($menuItem['name'], Url::to($menuItem['url']), ['class' => $menuItem['class'] . $active ]);
}
    
$html = <<<Html
    <div class="container">
        <div class="content">
            <div class="statistics-navbar">
                <div class="btn-group">
                    {$menu}
                </div>
            </div>
Html;
                
    $content = $html.$content.'</div></div>' . $this->render('submenu');
    echo $this->render('@app/views/layouts/main',['content' => $content]); 

?>
