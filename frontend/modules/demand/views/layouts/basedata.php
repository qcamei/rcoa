<?php

use frontend\modules\demand\assets\BasedataAssets;
use yii\web\View;
use yii\widgets\Breadcrumbs;
/**
 * 基础数据布局文件，主要在 demand 布局文件上添加了 navbar 头部导航
 */

/* @var $this View */

/* 添加基础数据头部导航 */
$breadcrumbs = Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb basedata-breadcrumbs'],
            'homeLink'=>false,
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]);
$head = '<div class="container">'
            .$this->render('../basedata/_navbar')
            .$breadcrumbs
        .'</div>';
$content = $head.$content;

echo $this->render('demand',['content'=>$content]);

//注册基础数据资源
BasedataAssets::register($this);
?>