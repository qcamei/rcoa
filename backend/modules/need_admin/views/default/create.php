<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\workitem\Workitem */

$this->title = Yii::t('rcoa/workitem', 'Create Workitem');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/workitem', 'Workitems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workitem-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
