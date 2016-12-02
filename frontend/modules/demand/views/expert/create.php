<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\expert\Expert */

$this->title = Yii::t('rcoa/demand', 'Create Expert');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Experts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
