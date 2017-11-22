<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\mconline\McbsActivityType */

$this->title = Yii::t('null', '{Create}{Activity}{Type}', [
            'Create' => Yii::t('app', 'Create'),
            'Activity' => Yii::t('app', 'Activity'),
            'Type' => Yii::t('app', 'Type'),
        ]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('null', '{Activity}{Type}{Administration}', [
        'Activity' => Yii::t('app', 'Activity'),
        'Type' => Yii::t('app', 'Type'),
        'Administration' => Yii::t('app', 'Administration'),
    ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mcbs-activity-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
