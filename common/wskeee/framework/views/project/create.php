<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\Project */

$this->title = Yii::t('rcoa/framework', 'Create Project');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/framework', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="framework-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'parents' => $parents
    ]) ?>

</div>
