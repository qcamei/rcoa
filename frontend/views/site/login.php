<?php

use common\models\LoginForm;
use frontend\views\SiteAsset;
use yii\bootstrap\ActiveForm;
use yii\web\View;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model LoginForm */

$this->title = '用户登录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="site-login">
        
    </div>
</div>


<?php
    SiteAsset::register($this);
?>