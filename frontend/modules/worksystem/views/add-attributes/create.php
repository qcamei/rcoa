<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemAddAttributes */

$this->title = Yii::t('rcoa/worksystem', 'Create Worksystem Add Attributes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Add Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worksystem-add-attributes-create">

    <?= $this->render('_form', [
        'datas' => $datas,
    ]) ?>

</div>
