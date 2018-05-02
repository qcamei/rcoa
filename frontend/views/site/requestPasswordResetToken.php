<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '密码重置请求';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="site-request-password-reset">
        <h1><?= Html::encode($this->title) ?></h1>

        <p>请输入与你账号关联的邮箱，密码重置连接将发送到该邮箱。</p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                    <?= $form->field($model, 'email')->label('邮箱') ?>

                    <div class="form-group">
                        <?= Html::submitButton('发送', ['class' => 'btn btn-primary']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
