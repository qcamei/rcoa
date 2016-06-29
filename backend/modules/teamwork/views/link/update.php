<?php

use common\models\teamwork\Link;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Link */

$this->title = Yii::t('rcoa/teamwork', 'Update Link') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="link-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'phases' => $phases,
    ]) ?>

</div>
