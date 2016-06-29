<?php

use common\models\teamwork\PhaseLink;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model PhaseLink */

$this->title = Yii::t('rcoa/teamwork', 'Create Phase Link');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Phase Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phase-link-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
