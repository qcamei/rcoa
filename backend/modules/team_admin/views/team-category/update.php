<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\team\TeamCategory */

$this->title = Yii::t('rcoa/team', '{Update} {Team}{Category}: ', [
    'Update' => Yii::t('rcoa', 'Update'),
    'Team' => Yii::t('rcoa/team', 'Team'),
    'Category' => Yii::t('rcoa/team', 'Category'),
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', '{Team}{Categories}',['Team'=>  Yii::t('rcoa/team', 'Team'),'Categories'=>  Yii::t('rcoa/team', 'Categories')]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="team-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
