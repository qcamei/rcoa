<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
/* @var $this View */
/* @var $form ActiveForm */
?>
<h1>导入结果</h1>

<div class="import-result">
    
    <div class="result">
        
        <?php foreach($logs as $log): ?>
        
        <h3><?= Html::encode($log['stepName']) ?></h3>      
        
            <?php foreach($log['content'] as $key => $value): ?>
        
            <span><?= $key ?></span> => <span><?= $value ?></span><br/>
            
            <?php endforeach; ?>
            
        <?php endforeach; ?>
        
        
    </div>
    <p>
        <?= Html::a(Yii::t('rcoa', 'Back'), '#', ['class' => 'btn btn-default', 'onclick'=> 'history.go(-1)']) ?>
    </p>
</div>

<style type="text/css">
    .import-result .result{
        width: 100%;
        min-height: 400px;
        padding: 15px;
        margin-bottom: 10px;
        border: 1px #ccc solid;
        border-radius: 10px; 
    }
    .red{
        color: #f00;
    }
</style>