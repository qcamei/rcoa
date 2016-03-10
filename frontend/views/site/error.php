<?php

/* @var $this View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\web\View;

$this->title = $name;
?>
<div class="container">
    <div class="site-error">

        <h1><?= Html::encode($this->title) ?></h1>

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>
        
        <p>
            <?=  Html::a('返回', '', ['onclick'=>'history.go(-1)'])?>
        </p>
        <p>
            很抱歉！服务器在处理您的请求时发生了错误！
        </p>
        <p>
            请及时联系我们并反馈以上错误，谢谢！
        </p>

    </div>
</div>
