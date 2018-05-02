<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\team\TeamCategoryMap */

$this->title = Yii::t('rcoa/team', '{Update} {Team}{Category} {Map}',[
    'Update'=>  Yii::t('rcoa', 'Update'),
    'Team'=>  Yii::t('rcoa/team', 'Team'),
    'Category'=>  Yii::t('rcoa/team', 'Category'),
    'Map'=>  Yii::t('rcoa/team', 'Map'),
]);
$this->params['breadcrumbs'][] = ['label' => $model->teamCategory->name, 'url' => ['team-category/view','id'=>$model->teamCategory->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-category-map-update">

    <?= $this->render('_form', [
        'model' => $model,
        'teams'=> $teams,
        'teamCategorys'=> $teamCategorys,
        'isUpdate'=>true,
    ]) ?>

</div>
