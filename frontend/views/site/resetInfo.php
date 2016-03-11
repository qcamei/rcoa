<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$this->title = '我的属性';
?>

<h2 style="margin-left: 40px;">我的属性修改</h2>

<div class="container has-title" style="margin-top: -30px;">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>