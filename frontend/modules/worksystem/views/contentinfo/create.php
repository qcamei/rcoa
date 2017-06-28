<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemContentinfo */

$this->title = Yii::t('rcoa/worksystem', 'Create Worksystem Contentinfo');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Contentinfos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-contentinfo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
