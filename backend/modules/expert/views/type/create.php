<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\expert\ExpertType */

$this->title = Yii::t('rcoa', 'Create Expert Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa', 'Expert Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
