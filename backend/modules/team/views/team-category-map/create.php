<?php

use common\models\team\TeamCategoryMap;
use yii\web\View;


/* @var $this View */
/* @var $model TeamCategoryMap */

$this->title = Yii::t('rcoa/team', '{Create} {Team}{Category} {Map}',[
    'Create'=>  Yii::t('rcoa', 'Create'),
    'Team'=>  Yii::t('rcoa/team', 'Team'),
    'Category'=>  Yii::t('rcoa/team', 'Category'),
    'Map'=>  Yii::t('rcoa/team', 'Map'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/team', '{Team}{Categories}',['Team'=>  Yii::t('rcoa/team', 'Team'),'Categories'=>  Yii::t('rcoa/team', 'Categories')]), 'url' => ['/teammanage/team-category/index']];
$this->params['breadcrumbs'][] = ['label' => $model->teamCategory->name, 'url' => ['team-category/view','id'=>$model->teamCategory->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-category-map-create">

    <?= $this->render('_form', [
        'model' => $model,
        'teams'=>$teams,
        'teamCategorys'=>$teamCategorys,
    ]) ?>

</div>
