<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$this->title = '我的属性';
?>

<div class="container">
    <h2 style="margin:25px 0 20px -5px;">我的属性修改</h2>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>