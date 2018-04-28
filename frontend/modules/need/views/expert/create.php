<?php

use frontend\modules\need\models\BasedataExpert;
use yii\web\View;


/* @var $this View */
/* @var $model BasedataExpert */

$this->title = Yii::t('app', '{Create} {Expert}',['Create'=>  Yii::t('app', 'Create'),'Expert'=>  Yii::t('app', 'Expert')]);
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="main expert-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
