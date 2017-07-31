<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\teamwork\TemplateType */

$this->title = Yii::t('rcoa/teamwork', 'Update Template Type') . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Template Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="template-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
