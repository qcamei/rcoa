<?php

use mconline\modules\mcqj\assets\McqjAssets;
use yii\helpers\Html;
    
    $this->title = Yii::t(null, '{Mcqj}{Courses}', [
        'Mcqj' => Yii::t('app', '情景'),
        'Courses' => Yii::t('app', 'Courses'),
    ]);
?>

<div class="mcjq-default-index">
    <?= Html::img('/upload/site/images/404.jpg', ['width' => '100%']) ?>
</div>


<?php
    McqjAssets::register($this);
?>