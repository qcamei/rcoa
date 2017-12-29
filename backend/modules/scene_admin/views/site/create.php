<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\scene\SceneSite */

$this->title = Yii::t('app', 'Create Scene Site');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Scene Sites'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-site-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
