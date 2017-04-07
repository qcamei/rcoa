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
            
            <div class="site-login-logo">
                <?= Html::img(['/filedata/site/image/site_logo.png'])?>
            </div>
            
            <div class="site-login-introduction">
                <span>课程建设分散式众包平台</span>
            </div>
            
            <div class="site-login-form">
                <div class="col-lg-9 site-login-case">

                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username',[
                        'options' => [
                            'class' => 'col-lg-4',
                        ],
                        'template' => "<div class=\"col-lg-1 site-login-icon\"><img src=\"/filedata/site/image/user_name.png\"></div><div class=\"col-lg-10\" style=\"padding:0px;\">{input}</div>\n<div class=\"col-lg-10\" style=\"padding: 0px 5px;\">{error}</div>"
                    ]); ?>

                    <?= $form->field($model, 'password', [
                        'options' => [
                            'class' => 'col-lg-4',
                        ],
                        'template' => "<div class=\"col-lg-1 site-login-icon\"><img src=\"/filedata/site/image/password.png\"></div><div class=\"col-lg-10\" style=\"padding:0px;\">{input}</div>\n<div class=\"col-lg-10\" style=\"padding: 0px 5px;\">{error}</div>"
                    ])->passwordInput() ?>

                    <div class="col-lg-4">
                    <?= Html::submitButton('登录', [
                        'name' => 'login-button', 
                        'class' => 'btn btn-primary col-lg-9', 
                    ]) ?>
                    </div>

                    <?= $form->field($model, 'rememberMe', [
                        'options' => [
                            'class' => 'col-lg-4',
                        ],
                        //'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>",
                    ])->checkbox([
                        'template' => "<div class=\"checkbox\"><label for=\"loginform-rememberme\">{input}自动登录</label></div>"
                    ]) ?>

                <?php ActiveForm::end(); ?>

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
JS;
    $this->registerJs($js, View::POS_READY);
?>

<?php
    SiteAsset::register($this);
?>