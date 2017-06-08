<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model common\modules\rbac\models\AuthItem */
/* @var $form ActiveForm */
?>

<div class="user-role-form">

    <?php $form = ActiveForm::begin([
        'id' => 'user-role-create-form',
    ]); ?>

    <div class="user-role-create-form">
        
        <?php foreach($roleCategorys as $roleCategory): ?>
        
        <p><b><?= $roleCategory['name'] ?></b></p>
            <?php foreach($roles as $roleItems): ?>
                <?php if($roleItems->system_id == $roleCategory['id']): ?>
                <p style="padding-left: 20px;">
                    <?= Html::checkbox('item_name[]', '', ['value' => $roleItems->name]) ?><?= $roleItems->description ?>
                </p>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
                
    </div>
   
    <?php ActiveForm::end(); ?>

</div>
