<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\scene\SceneAppraise */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Scene Appraise',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Scene Appraises'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="scene-appraise-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
