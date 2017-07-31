<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\teamwork\TemplateType */

$this->title = Yii::t('rcoa/teamwork', 'Create Template Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Template Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
