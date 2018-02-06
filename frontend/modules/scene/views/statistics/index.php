<?php

use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('app', '{Scene}-{Statistics}',[
    'Scene' => Yii::t('app', 'Scene'),
    'Statistics' => Yii::t('app', 'Statistics'),
]);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="scene-statistics-index">
    <?= Html::img('/filedata/scene/404.jpg', ['width' => '100%']) ?>
</div>