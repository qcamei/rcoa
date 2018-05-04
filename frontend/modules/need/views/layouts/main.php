<?php

use frontend\modules\need\assets\MainAssets;
use yii\web\View;
/**
 * 课程需求布局文件，主要在 main 布局文件上添加了 面包屑 or 子菜单
 */

/* @var $this View */

//注册需求资源
MainAssets::register($this);

// 添加面包屑 or 子菜单
if(in_array(Yii::$app->controller->action->id, ['index', 'list'])){
    $content = $content . $this->render('submenu');
}else{
    $content = $this->render('crumbs') . $content;
}

echo $this->render('@app/views/layouts/main',['content' => $content]);

?>