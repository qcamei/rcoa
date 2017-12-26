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
    <div class="course">
        <div class="platform container">
            <div class="logo">
                <?= Html::img(['/filedata/site/image/small_logo.png'], ['width'=>'100%']) ?>
            </div> 
            <div class="name">
                <p><span class="CHS"><?= Html::encode('课程建设工作平台') ?></span></p>
                <span class="EN">The Platform Of Curriculum Construction</span>
            </div> 
            <div class="frame">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username',[
                        'options' => [
                            'class' => 'col-xs-12 attribute',
                        ],
                        'template' => "<div class=\"col-xs-12 icon\"><img src=\"/filedata/site/image/user_name.png\"></div><div class=\"col-xs-10\" style=\"padding:0px;\">{input}</div>\n<div class=\"col-xs-10\" style=\"padding: 0px 5px;\">{error}</div>"
                    ]); ?>

                    <?= $form->field($model, 'password', [
                        'options' => [
                            'class' => 'col-xs-12 attribute',
                        ], 
                        'template' => "<div class=\"col-xs-12 icon\"><img src=\"/filedata/site/image/password.png\"></div><div class=\"col-xs-10\" style=\"padding:0px;\">{input}</div>\n<div class=\"col-xs-10\" style=\"padding: 0px 5px;\">{error}</div>"
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
    SiteAsset::register($this);
?>