<?php

use frontend\modules\demand\models\BasedataExpert;
use yii\web\View;


/* @var $this View */
/* @var $model BasedataExpert */

$this->title = Yii::t('rcoa/basedata', '{Create} {Expert}',['Create'=>  Yii::t('rcoa/basedata', 'Create'),'Expert'=>  Yii::t('rcoa/basedata', 'Expert')]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container expert-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
