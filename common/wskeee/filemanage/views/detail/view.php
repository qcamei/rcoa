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

<div class="container file-manage-view">
    <center><h1 style="font-family:微软雅黑;color:#467d19;"><?= Html::encode($model->name) ?></h1></center>
    <hr>
    <?php 
        if($model->getFmUpload())
            echo '<iframe style="width:100%;height:600px;" src="http://officeweb365.com/o/?i=6824&furl=http://eefile.gzedu.com'.$model->file_link.'"></iframe>';
        else
            echo $model->filemanageDetail->content; 
        
    ?>
    
    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['/filemanage/default/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['/filemanage/default/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa/fileManage', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <!--<?php /* DetailView::widget([
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
    ]) */?>-->

</div>
