<?php

use frontend\views\SiteAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/*has-title*/
$this->title = '课程中心工作平台';
?>

<?= Html::img(['/filedata/site/banner/banner.jpg'], ['width' => '100%']) ?>

<div class="container site-index">
    <div class="jumbotron">
        <div class="row hidden-xs">
            <?php foreach ($system as $value){
                 echo '<div class="col-lg-2 col-md-3 col-sm-4 modules-list">';
                 echo Html::a(Html::img($value->module_image,[
                         'class' => 'center-block',
                         'width' => '150',
                         'height' => '150',
                         'alt' => $value->des,
                     ]), $value->module_link,[
                            'title' => $value->module_link != '#' ? $value->name : '即将上线',
                    ]);
                 echo '</div>';
             }?>
        </div>
        <div class="row visible-xs-inline-block">
            <?php foreach ($system as $value){
                 echo '<div class="col-xs-6 modules-list">';
                 echo Html::a(Html::img($value->module_image,[
                         'class' => 'center-block',
                         'width' => '100',
                         'height' => '100',
                         'alt' => $value->des,
                     ]), $value->module_link,[
                            'title' => $value->module_link != '#' ? $value->name : '即将上线',
                    ]);
                 echo '</div>';
             }?>
        </div>
    </div>
</div>
<?php  
 $js =   
<<<JS
    
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 


<?php
    SiteAsset::register($this);
?>