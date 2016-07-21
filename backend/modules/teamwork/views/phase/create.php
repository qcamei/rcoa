<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\Phase */

$this->title = Yii::t('rcoa/teamwork', 'Create Phase');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Phases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phase-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'templateType' => $templateType,
    ]) ?>

</div>
