<?php

use wskeee\framework\models\College;
use yii\web\View;


/* @var $this View */
/* @var $model College */

$this->title = Yii::t('rcoa/basedata', '{Create}',['Create'=>  Yii::t('rcoa/basedata', 'Create')]);
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="main college-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
