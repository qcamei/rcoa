<?php

use common\models\worksystem\WorksystemContent;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model WorksystemContent */

$this->title = Yii::t('rcoa/worksystem', 'Create Worksystem Content');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-content-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'taskTypes' => $taskTypes,
    ]) ?>

</div>
