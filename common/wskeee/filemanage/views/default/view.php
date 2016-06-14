<?php

use wskeee\filemanage\models\FileManage;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model FileManage */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/fileManage', 'File Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-manage-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/fileManage', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => $model->type == FileManage::FM_FILE ?  '文档' : '目录',
            ],
            'name',
            [
                'attribute' => 'pid',
                'format' => 'raw',
                'value' => !isset($model->pid) ? '<span style="color:red">Null</span>' : $model->fileManagePid->name,
            ],
            'keyword',
            [
                'attribute' => 'icon',
                'format' => 'raw',
                'value' => '<i class="'.$model->icon.'"></i>',
            ],
        ],
    ]) ?>

</div>
