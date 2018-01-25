<?php

use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

//$this->title = Yii::t('app', 'Mcbs Courses');
$this->params['breadcrumbs'][] = $this->title;

?>

<ul class="time-vertical">
    
    <?php foreach($dataProvider->models AS $item): ?>
    <li>
        <b></b>
        <?= Html::img($item->createdBy->avatar, ['class'=>'img-circle']) ?>
        <div class="msg-frame">
            <p>
                <span class="username"><?= Html::encode($item->createdBy->nickname) ?></span>
                <span class="time">发于<?= date('Y-m-d H:i:s', $item->created_at) ?></span>
            </p>
            <span>
                <?= $item->content ?>
            </span>
        </div>
    </li>
    <?php endforeach; ?>
    
</ul>
    

<?php
$js = 
<<<JS
   
        
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    //McbsAssets::register($this);
?>