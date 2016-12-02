<?php

use frontend\modules\demand\assets\BasedataAssets;
use yii\web\View;
/**
 * 基础数据布局文件，主要在 demand 布局文件上添加了 navbar 头部导航
 */

/* @var $this View */

/* 添加基础数据头部导航 */
$content = $this->render('../basedata/_navbar').$content;

echo $this->render('demand',['content'=>$content]);

//注册基础数据资源
BasedataAssets::register($this);
?>