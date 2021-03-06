<?php

use backend\assets\AppAsset;
use common\models\User;
use common\modules\helpcenter\assets\HelpCenterAssets;
use dmstr\web\AdminLteAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $content string */
/* @var $user User */

if (class_exists('backend\assets\AppAsset')) {
    AppAsset::register($this);
} else {
    app\assets\AppAsset::register($this);
}
AdminLteAsset::register($this);

$user = Yii::$app->user->identity;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <?php $this->beginBody() ?>
        <div class="wrapper">

            <?= $this->render('navbar.php',[
                'app_id' => $this->params,
                'user' => $user
            ]);?>
            
            <?= $this->render('left.php', $this->params);?>
            
            <div class="content-wrapper">
                <section class="content">
                    <?= $content ?>
                </section>
            </div>

        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
<?php
    HelpCenterAssets::register($this);
?>