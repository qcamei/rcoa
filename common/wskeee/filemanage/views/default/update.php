<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wskeee\filemanage\models\FileManage */

$this->title = Yii::t('rcoa/fileManage', 'Update File Manage'). ' : ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/fileManage', 'File Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="file-manage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'owner' => $owner,
        'detail' => $detail,
        'ownerName' => $ownerName,
        'ownerValue' => $ownerValue,
        'fmList' => $fmList,
    ]) ?>

</div>
