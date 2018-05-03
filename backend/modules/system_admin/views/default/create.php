<?php

use common\models\System;
use yii\web\View;

/* @var $this View */
/* @var $model System */

$this->title = Yii::t('rcoa', '{Create}{Systems}',[
    'Create' => Yii::t('app', 'Create'),
    'Systems' => Yii::t('app', 'Systems'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa', 'Systems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-create">

    <?= $this->render('_form', [
        'model' => $model,
        'parentIds' => $parentIds,
    ]) ?>

</div>