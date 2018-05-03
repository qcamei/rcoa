<?php

use common\models\System;
use yii\web\View;

/* @var $this View */
/* @var $model System */

$this->title = Yii::t('rcoa', '{Update}{Systems}',[
    'Update' => Yii::t('app', 'Update'),
    'Systems' => Yii::t('app', 'Systems'),
]). ':' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa', 'Systems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');

?>
<div class="system-update">

    <?= $this->render('_form', [
        'model' => $model,
        'parentIds' => $parentIds,
    ]) ?>

</div>