<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\Link */

$this->title = Yii::t('rcoa/framework', 'Create Link');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/framework', 'Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
