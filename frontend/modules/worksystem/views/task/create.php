<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemTask */

$this->title = Yii::t('rcoa/worksystem', 'Create Worksystem Task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
