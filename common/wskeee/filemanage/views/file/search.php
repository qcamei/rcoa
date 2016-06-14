<?php

use common\wskeee\filemanage\FileManageAsset;
use wskeee\filemanage\models\FileManage;
use wskeee\filemanage\models\searchs\FileManageSearch;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model FileManageSearch */
/* @var $form ActiveForm */
$this->title =  Yii::t('rcoa/fileManage', 'File Manage Search');
?>

<div class="title">
    <div class="container">
        <?=$this->title. ' : ' .$keyword;?>
    </div>
</div>

<div class="container file-manage-search has-title">
    <?php
        if(empty($fmSearch)) echo '<h3>没有相关数据</h3>';
        foreach ($fmSearch as $value) {
            echo Html::beginTag('a',['href' => $value->type == FileManage::FM_FILE ? '/filemanage/file/view?id='.$value->id :'/filemanage/file/index?id='.$value->id]);
                echo Html::img([$value->image]);
                echo Html::beginTag('p',['style' => 'padding-top: 5px;']).
                     Html::beginTag('span').'名称:'.Html::endTag('span').$value->name.Html::endTag('p');
                echo Html::beginTag('p',['class' => 'position']).Html::beginTag('span').'位置:'.Html::endTag('span');
                    echo '首页 / ';
                    foreach ($fileManage->getFileManageBread($value->id) as $position)
                        echo $value->id == $position->id ?  '' : $position->name.' / ';
                echo Html::endTag('p');
            echo Html::endTag('a');
        }
    ?>
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-9 col-md-10 col-xs-7">
                <?= $this->render('_form_search') ?>
            </div>
            <?= Html::submitButton(Yii::t('rcoa', 'Search'), ['id' => 'submit','class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('rcoa', 'Back'), ['index'], ['class' => 'btn btn-default', 'onclick'=>'history.go(-1)']) ?>
        </div>
    </div>
</div>

<?php
    FileManageAsset::register($this);
?>

<?php  
$js =   
<<<JS
    
    
JS;
    $this->registerJs($js,  View::POS_READY);
?> 
