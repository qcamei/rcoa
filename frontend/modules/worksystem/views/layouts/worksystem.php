<?php

use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\web\View;
/**
 * 布局文件，主要在 main 布局文件上添加了 footer 尾部导航
 */

/* @var $this View */

/* 添加尾部导航 */
$content = $content.$this->render('_footer');

echo $this->render('@app/views/layouts/main', ['content'=>$content]);

//注册课程需求资源
WorksystemAssets::register($this);
?>