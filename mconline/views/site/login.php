<?php

use common\models\LoginForm;
use mconline\assets\AppAsset;
use mconline\assets\SiteAssets;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model LoginForm */

$this->title = '用户登录';

?>

<div class="site-login">
    <div class="mconline" style='background-image: url("/upload/site/images/site_loginbg.jpg");'>
        <div class="platform container" style='background-image: url("/upload/site/images/site_loginboxbg.png");'>
            <div class="logo">
                <?= Html::img('/upload/site/images/small_logo.png', ['width'=>'100%']) ?>
            </div> 
            <div class="name">
                <p><span class="CHS"><?= Html::encode('在线制作课程平台') ?></span></p>
                <span class="EN">Online Making Of Course Platform</span>
            </div> 
            <div class="frame">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                
                    <?= $form->field($model, 'username',[
                        'options' => [
                            'class' => 'col-xs-12 attribute',
                        ],
                        'template' => "<div class=\"col-xs-12 icon\"><img src=\"/upload/site/images/user_name.png\"></div><div class=\"col-xs-10\" style=\"padding:0px;\">{input}</div>\n<div class=\"col-xs-10\" style=\"padding: 0px 5px;\">{error}</div>"
                    ]); ?>

                    <?= $form->field($model, 'password', [
                        'options' => [
                            'class' => 'col-xs-12 attribute',
                        ], 
                        'template' => "<div class=\"col-xs-12 icon\"><img src=\"/upload/site/images/password.png\"></div><div class=\"col-xs-10\" style=\"padding:0px;\">{input}</div>\n<div class=\"col-xs-10\" style=\"padding: 0px 5px;\">{error}</div>"
                    ])->passwordInput() ?>
                    <?= $form->field($model, 'rememberMe', [
                        'options' => [
                            'class' => 'col-xs-12',
                        ],
                        //'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>",
                    ])->checkbox([
                        'template' => "<div class=\"checkbox\"><label for=\"loginform-rememberme\">{input}自动登录</label></div>"
                    ]) ?>
                    <div class="col-xs-9 button">
                        <?= Html::submitButton('登录', [
                            'name' => 'login-button', 
                            'class' => 'btn btn-primary col-xs-12', 
                        ]) ?>
                    </div>
                
               <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<?php
$js = <<<JS
   
    /** 滚动到登录框 */
    $('html,body').animate({scrollTop: ($(".platform").offset().top) - 100}, 200);
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    AppAsset::register($this);
    SiteAssets::register($this);
?>