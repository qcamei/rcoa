<?php

use common\wskeee\filemanage\FileManageAsset;
use wskeee\filemanage\models\FileManage;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model FileManage */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/fileManage', 'File Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_title',['get' => $get, 'bread' => $bread]) ?>

<div class="container file-manage  has-title">
    <div class="row">
        <div id="cbp-spmenu-s1" class="col-xs-3 col-sm-3 col-md-3 cbp-spmenu cbp-spmenu-open">
            <?= $this->render('_leftList',['get' => $get, 'list' => $list]) ?>
        </div>
        <div id="cbp-spmenu-s2" class="col-xs-9 col-sm-9 col-md-9">
            <?php
                echo Html::beginTag('div',['id' => 'showLeftPush', 'class' => 'col-xs-1 col-sm-1 col-md-1']);
                    echo Html::img('/filedata/image/sidebar-arrow-left.jpg', ['id'=>'showLeftImg']);
                echo Html::endTag('div');
            ?>
            <div id="content" class="col-xs-11 col-sm-11 col-md-11">
                <center><h3 style="font-family:微软雅黑;color:#467d19;"><?= Html::encode($model->name) ?></h3></center>
                <hr>
                <?php 
                    if($model->getFmUpload())
                        echo '<iframe class="col-md-11" style="width:100%;" src="http://officeweb365.com/o/?i=6824&furl=http://eefile.gzedu.com'.$model->file_link.'"></iframe>';
                    else
                        echo $model->filemanageDetail->content; 
                ?>
            </div>
        </div>
    </div>
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-9 col-md-10 col-xs-7">
                <?= $this->render('_form_search') ?>
            </div>
            <?= Html::submitButton(Yii::t('rcoa', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('rcoa', 'Back'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php
    FileManageAsset::register($this);
?>
<?= $this->render('_file_js') ?>