<?php

use common\models\expert\Expert;
use yii\web\View;

/* @var $this View */
/* @var $model Expert */

$this->title = Yii::t('app', 'Update'). '：' . $model->nickname;
$this->params['breadcrumbs'][] = Yii::t('app', 'Update').'：'.$model->nickname;

?>

<div class="main expert-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
