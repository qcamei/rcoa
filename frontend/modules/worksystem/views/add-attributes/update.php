<?php

use common\models\worksystem\WorksystemAddAttributes;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model WorksystemAddAttributes */

$this->title = Yii::t('rcoa/worksystem', 'Update Worksystem Add Attributes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Add Attributes'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/worksystem', 'Update');
?>

<div class="worksystem-add-attributes-update">

    <?= $this->render('_form', [
        'datas' => $datas,
    ]) ?>

</div>
