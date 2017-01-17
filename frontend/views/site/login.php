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
        <div class="login-box">
        <div class="login-newbg">
            <div class="content-layout">
                <div class="login-box-warp">
                    <div class="box-warp-header"><?= $this->title; ?></div>
                    <div class="box-warp-footer">
                        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                            <div class="login-form">
                                
                            <?= $form->field($model, 'username',[
                                'options' => [
                                    'style' => 'width:220px;height:34px;margin-bottom:5px;',
                                ],
                                'template' => "<div class=\"col-lg-2 left\" style=\"padding:0px;\"><img src=\"/filedata/site/image/user_name.png\"></div><div class=\"col-lg-10 right\" style=\"padding:0px;\">{input}</div>"
                            ]); ?>

                            <?= $form->field($model, 'password', [
                                'options' => [
                                    'style' => 'width:220px;height:34px;',
                                ],
                                'template' => "<div class=\"col-lg-2 left\" style=\"padding:0px;\"><img src=\"/filedata/site/image/password.png\"></div><div class=\"col-lg-12 right\" style=\"padding:0px;\">{input}</div>\n<div class=\"col-lg-12\" style=\"padding:0px;\">{error}</div>"
                            ])->passwordInput() ?>

                            <?= $form->field($model, 'rememberMe', [
                                'options' => [
                                    'style' => 'padding:0px;',
                                ],
                                //'template' => "{label}\n<div class=\"col-lg-12\">{input}</div>",
                            ])->checkbox([
                                'template' => "<div class=\"checkbox\"><label for=\"loginform-rememberme\">{input}自动登录</label></div>"
                            ]) ?>
                                
                            <?= Html::submitButton('登录', ['class' => 'btn btn-primary login-button', 'name' => 'login-button']) ?>
                            
                            </div>
                                                        
                        <?php ActiveForm::end(); ?>
                            
                        <div class="line"><div class="margin-line"><div class="padding-line"></div></div></div>
                        
                        <div class="login-form" style="margin-top: 0px;">
                            <?= Html::submitButton('游客登录', ['class' => 'btn tourist-button disabled', 'name' => 'tourist-button']) ?>
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </div>
        </div>
    </div>

<?php
    SiteAsset::register($this);
?>