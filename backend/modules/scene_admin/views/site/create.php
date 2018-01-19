<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\scene\SceneSite */

$this->title = Yii::t('app', '{Create}{Scene}',[
    'Create' => Yii::t('app', 'Create'),
    'Scene' => Yii::t('app', 'Scene'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '{Scene}{List}{Administration}',[
    'Scene' => Yii::t('app', 'Scene'),
    'List' => Yii::t('app', 'List'),
    'Administration' => Yii::t('app', 'Administration'),
]), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-site-create">
    
<h1><?php //Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'area' => $area,
        'manager' => $manager,
        'point' => $point,
    ]) ?>

</div>
