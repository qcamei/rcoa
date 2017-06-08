<?php

use wskeee\rbac\models\AuthItem;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model AuthItem */

$this->title = 'Create Auth Item';
$this->params['breadcrumbs'][] = ['label' => 'Auth Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-manager-create rbac">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categorys' => $categorys,
    ]) ?>

</div>
