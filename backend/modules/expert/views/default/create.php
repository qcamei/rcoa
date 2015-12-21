<?php

use backend\modules\expert\models\ExpertCreateForm;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model ExpertCreateForm */

$this->title = Yii::t('rcoa', 'Create Expert');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa', 'Experts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expert-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
