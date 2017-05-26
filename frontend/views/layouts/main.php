<?php

/* @var $this View */
/* @var $content string */

use kartik\widgets\AlertBlock;
use yii\helpers\Html;
use yii\web\View;


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="image/x-icon" href="/gongjiang.ico" rel="shortcut icon">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
        echo $this->render('_navbar');
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
</div>

<footer class="footer">
    <div class="container" style="padding: 3px 10px;">
        <p class="pull-left" style="margin-top: 15px;">&copy; 国家开放大学在线教育运营服务中心&nbsp;&nbsp;版本号：v1.50 </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
