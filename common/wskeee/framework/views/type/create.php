<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\ItemType */

$this->title = Yii::t('rcoa/framework', 'Create Item Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/framework', 'Item Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
