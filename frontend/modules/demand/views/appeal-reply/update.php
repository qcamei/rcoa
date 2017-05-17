<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandReply */

$this->title = Yii::t('rcoa/demand', 'Update {modelClass}: ', [
    'modelClass' => 'Demand Reply',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Replies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/demand', 'Update');
?>
<div class="demand-reply-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
