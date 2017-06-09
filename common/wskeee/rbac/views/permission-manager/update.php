<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\rbac\models\Permission */

$this->title = '更新权限: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Permissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="permission-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categorys' => $categorys,
        'rules' => $rules
    ]) ?>

</div>
