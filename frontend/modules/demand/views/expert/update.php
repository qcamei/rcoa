<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\expert\Expert */

$this->title = Yii::t('rcoa/basedata', 'Update'). '：' . $model->nickname;
$this->params['breadcrumbs'][] = Yii::t('rcoa/basedata', 'Update').'：'.$model->nickname;
?>
<div class="container expert-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
