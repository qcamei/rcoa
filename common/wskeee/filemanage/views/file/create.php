<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model wskeee\filemanage\models\FileManage */

$this->title = Yii::t('rcoa/fileManage', 'Create File Manage');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/fileManage', 'File Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-manage-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
