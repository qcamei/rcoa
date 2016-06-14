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

<?= $this->render('_title',['bread' => $bread]) ?>

<div class="container file-manage  has-title">
    <div class="row">
        <div id="cbp-spmenu-s1" class="col-xs-3 col-sm-3 col-md-3 cbp-spmenu cbp-spmenu-open">
            <?= $this->render('_leftList',['list' => $list]) ?>
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
                        echo Html::a('<div class="fm-md-3 course-name">'
                                . '<p><img src="'.$value->image.'" style="height:80px;"></p>'
                                .$value->name.'</div>', [$value->type == FileManage::FM_FILE ? 'view' :'index', 'id' => $value->id],
                                ['title' => $value->name]);
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
    FileManageAsset::register($this);
?>

<?php  
$js =   
<<<JS
    var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
        menuRight = document.getElementById( 'cbp-spmenu-s2' ),
        showLeftImg = document.getElementById( 'showLeftImg' ),
        showLeftPush = document.getElementById( 'showLeftPush' ),
        className = 'col-xs-1 col-sm-1 col-md-1 active';
    
    if($(window).width() <= 768){
        classie.toggle( menuLeft, 'cbp-spmenu-left');
        classie.toggle( menuRight, 'cbp-spmenu-right');
        showLeftImg.src = '/filedata/image/sidebar-arrow-right.jpg';
        showLeftPush.onclick = function() {
            classie.toggle( this, 'active' );
            classie.toggle( menuLeft, 'cbp-spmenu-left');
            classie.toggle( menuRight, 'cbp-spmenu-right');
            if(showLeftPush.className == className)
                showLeftImg.src = '/filedata/image/sidebar-arrow-left.jpg';
            else
                showLeftImg.src = '/filedata/image/sidebar-arrow-right.jpg';
        };
    }
    else{
        showLeftPush.onclick = function() {
            classie.toggle( this, 'active' );
            classie.toggle( menuLeft, 'cbp-spmenu-left' );
            classie.toggle( menuRight, 'cbp-spmenu-right');
            if(showLeftPush.className == className)
                showLeftImg.src = '/filedata/image/sidebar-arrow-right.jpg';
            else
                showLeftImg.src = '/filedata/image/sidebar-arrow-left.jpg';
        };
    }
    
JS;
    $this->registerJs($js,  View::POS_READY);
?> 