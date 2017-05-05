<?php

use common\models\LoginForm;
use frontend\views\SiteAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $form ActiveForm */
/* @var $model LoginForm */

$this->title = '用户登录';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
    <div class="container">
        <div class="site-login-box">
            <div class="site-login-form">
                <div class="site-login-logo">
                    <?= Html::img(['/filedata/site/image/logo.png'])?>
                    <!--<span>课程建设分散式众包平台</span>-->
                </div>
                <div class="site-login-case">
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                        <?= $form->field($model, 'username',[
                            'options' => [
                                'class' => 'col-xs-12 site-login-attribute',
                            ],
                            'template' => "<div class=\"col-xs-12 site-login-icon\"><img src=\"/filedata/site/image/user_name.png\"></div><div class=\"col-xs-10\" style=\"padding:0px;\">{input}</div>\n<div class=\"col-xs-10\" style=\"padding: 0px 5px;\">{error}</div>"
                        ]); ?>

                        <?= $form->field($model, 'password', [
                            'options' => [
                                'class' => 'col-xs-12 site-login-attribute',
                            ], 
                            'template' => "<div class=\"col-xs-12 site-login-icon\"><img src=\"/filedata/site/image/password.png\"></div><div class=\"col-xs-10\" style=\"padding:0px;\">{input}</div>\n<div class=\"col-xs-10\" style=\"padding: 0px 5px;\">{error}</div>"
                        ])->passwordInput() ?>
                        <?= $form->field($model, 'rememberMe', [
                            'options' => [
                                'class' => 'col-xs-12',
                            ],
                            //'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>",
                        ])->checkbox([
                            'template' => "<div class=\"checkbox\"><label for=\"loginform-rememberme\">{input}自动登录</label></div>"
                        ]) ?>
                        <div class="col-xs-9 site-login-button">
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
</div>


<?php
$js = <<<JS
   $(window).resize(function(){
        size();
    });
    size();
    function size(){
        var height = $(document.body).height() - 100;
        if(height < 820)
            height = 820;
        $(".site-login").css({width:'100%',height:height, display:"block"});
    }
    
    /** 滚动到登录框 */
    $('html,body').animate({scrollTop:($('.site-login-form').offset().top) - 140},1000);
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    SiteAsset::register($this);
?>