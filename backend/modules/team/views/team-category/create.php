<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\team\TeamCategory */

$this->title = Yii::t('rcoa/team', '{Create} {Team}{Category}',['Create'=>  Yii::t('rcoa', 'Create'),'Team'=>  Yii::t('rcoa/team', 'Team'),'Category'=>  Yii::t('rcoa/team', 'Category')]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', '{Team}{Categories}',['Team'=>  Yii::t('rcoa/team', 'Team'),'Categories'=>  Yii::t('rcoa/team', 'Categories')]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
