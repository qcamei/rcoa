<?php

use common\models\worksystem\WorksystemAttributesTemplate;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model WorksystemAttributesTemplate */

$this->title = Yii::t('rcoa/worksystem', 'Update Worksystem Attributes Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Attributes Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="worksystem-attributes-template-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'taskTypes' => $taskTypes,
        'attributes' => $attributes,
    ]) ?>

</div>
