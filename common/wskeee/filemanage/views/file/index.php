<?php

use common\wskeee\filemanage\FileManageAsset;
use wskeee\filemanage\FileManageTool;
use wskeee\filemanage\models\FileManage;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/fileManage', 'File Manages');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_title',['get' => $get, 'bread' => $bread]) ?>

<div class="container file-manage-index file-manage has-title">
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
            <div class="col-xs-11 col-sm-11 col-md-11">
                <?php
                    foreach ($list as $key => $value) {
                        $fileSuffix = pathinfo($value->file_link, PATHINFO_EXTENSION);
                        echo Html::a('<div class="fm-md-3 course-name">'
                                . '<p><img src="'.$value->image.'" style="height:80px;"></p>'
                                .$value->name.'</div>', 
                                $fileSuffix == 'rar' || $fileSuffix == 'zip' ?
                                    'http://eefile.gzedu.com'.$value->file_link :
                                    [$value->type != FileManage::FM_FOLDER ? 'view' :'index', 'id' => $value->id] ,
                                ['class' => (!isset($get['id'])? null : $get['id']) != $value->id ? :'disabled','title' => $value->name]);
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-10 col-md-11 col-xs-9">
                <?= $this->render('_form_search') ?>
            </div>
            <?= Html::submitButton(Yii::t('rcoa', 'Search'), ['id' => 'submit','class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php
    FileManageAsset::register($this);
?>
<?= $this->render('_file_js') ?>
