<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\resource\ResourcePath */

$this->title = Yii::t('rcoa/resource', 'Create Resource Path');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/resource', 'Resource Paths'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resource-path-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
