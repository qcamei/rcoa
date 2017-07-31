<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemAttributesTemplate */

$this->title = Yii::t('rcoa/worksystem', 'Create Worksystem Attributes Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Attributes Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-attributes-template-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'taskTypes' => $taskTypes,
        'attributes' => $attributes,
    ]) ?>

</div>
