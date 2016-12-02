<?php

use frontend\modules\demand\assets\BasedataAssets;
use wskeee\framework\models\ItemType;
use yii\web\View;
use yii\widgets\Breadcrumbs;


/* @var $this View */
/* @var $model ItemType */

$this->title = Yii::t('rcoa/basedata', '{Create} {Item Type}',[
    'Create'=>Yii::t('rcoa/basedata', 'Create'),
    'Item Type'=>Yii::t('rcoa/basedata', 'Item Type'),
    ]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container item-type-create">

    <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>