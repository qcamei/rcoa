<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\expert\ExpertProject */

$this->title = Yii::t('rcoa', 'Create Expert Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa', 'Expert Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-project-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
