<?php

use wskeee\framework\models\College;
use yii\web\View;

/* @var $this View */
/* @var $model College */

$this->title = Yii::t('rcoa/basedata', 'Update')."：".$model->name;
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="main college-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
