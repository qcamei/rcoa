<?php

use common\models\expert\searchs\ExpertSearch;
use frontend\modules\expert\ExpertAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model ExpertSearch */
/* @var $form ActiveForm */
$this->title =  Yii::t('rcoa', 'Expert Search') . ' : ' .$categories;
?>
<!-- title 样式 -->
<div class="title">
    <div class="container">
        <?=$this->title;?>
    </div>
</div>

<div class="container expert-type bookdetail-list has-title">
    <?php 
        if(count($modelKey) == 0)
            echo '<h2>未找到有关<span style="color:blue"> '. $categories .' </span>的专家资源！</h2>';
        foreach ($modelKey as $key){
            echo Html::a('<div class="expert"><div class="personal-image">'
                    .Html::img($key->personal_image, [
                        'class' => 'img-rounded',
                        'style' => 'margin:5px; border:1px solid #CCC',
                        'width' => '60',
                        'height' => '60',
                    ]). '</div>'
                    . '<div><span class="nickname"><b>'.$key->user->nickname.'('.$key->job_title.')</b></span>'
                    . '<p class="course-name"><span>职称：</span>'.$key->job_name.'</p>'
                    . '<p class="course-name" ><span>描述：</span>'.$key->attainment.'</p></div></div>', 
                    ['view','id'=> $key->u_id], ['id' => 'thelist']);
        }
    ?>
</div>

<div class="controlbar">
    <div class="container">
        <div class="row ">
            <div class="col-sm-9 col-md-10 col-xs-7">
                <?= $this->render('_form_search')?>
            </div>
            <?= Html::a(Yii::t('rcoa', 'Search'), 'javascript:;', ['id'=>'submit', 'class' => 'glyphicon glyphicon-search btn btn-default',]) ?>
            <?= Html::a(Yii::t('rcoa', 'Back'), '', ['class' => 'btn btn-default', 'onclick'=>'history.go(-1)']) ?>
        </div>
    </div>
</div>


<?php
    ExpertAsset::register($this);
?>