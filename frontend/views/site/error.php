<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        很抱歉！服务器在处理您的请求时发生了错误！
    </p>
    <p>
        请及时联系我们并反馈以上错误，谢谢！
    </p>

</div>
