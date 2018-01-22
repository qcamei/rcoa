<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\scene\SceneBook */

$this->title = Yii::t('app', 'Create Scene Book');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Scene Books'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-book-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
