<?php

use wskeee\framework\models\ItemType;
use yii\web\View;

/* @var $this View */
/* @var $model ItemType */

$this->title = Yii::t('rcoa/basedata', '{Create}',[
    'Create'=>Yii::t('rcoa/basedata', 'Create'),
    ]);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="main item-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>