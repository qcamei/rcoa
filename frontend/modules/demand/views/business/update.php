<?php

use frontend\modules\demand\assets\BasedataAssets;
use wskeee\framework\models\ItemType;
use yii\web\View;

/* @var $this View */
/* @var $model ItemType */

$this->title = Yii::t('rcoa/basedata', 'Update'). '：' . $model->name;
$this->params['breadcrumbs'][] = Yii::t('rcoa/basedata', 'Update');
?>
<div class="container item-type-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>