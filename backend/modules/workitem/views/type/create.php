<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\workitem\WorkitemType */

$this->title = Yii::t('rcoa/workitem', 'Create Workitem Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/workitem', 'Workitem Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workitem-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
