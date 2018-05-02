<?php

use common\models\resource\Resource;
use common\models\resource\ResourcePath;
use yii\web\View;

/* @var $this View */
/* @var $model Resource */


?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h5 style="margin:0; padding:0"><?= $model->name?></h5>
</div>

<div class="carousel slide" id="carousel-731952">
    <ol class="carousel-indicators">
         <?php 
            foreach ($path as $key => $value){
            if($key == 0)
                echo '<li class="active" data-slide-to="'.$key.'" data-target="#carousel-731952"></li>';
            else
                echo '<li data-slide-to="'.$key.'" data-target="#carousel-731952"></li>';
        }?>
      
    </ol>
    <div class="carousel-inner">
        <?php foreach ($path as $key => $value){
            if($key == 0)
                echo '<div class="item active">';
            else
                echo '<div class="item">';
            if($value->type == 1)
                echo '<video id="myVideo" class="img-responsive center-block" src="'.$value->path.'" type="video/mp4" controls>您的浏览器不支持 video 标签。</video>';
            else{
                echo '<img class="center-block img-responsive center-block" alt="" src="'.$value->path.'" />';
                echo '<div class="carousel-caption" style="display:none"><p>'.$value->des.'</p></div>';
            }
            echo '</div>';
        }?>
       
    </div> 
    <a data-slide="prev" href="#carousel-731952" class="left carousel-control">‹</a> 
    <a data-slide="next" href="#carousel-731952" class="right carousel-control">›</a>
    <img class="display" alt="" src="/css/imgs/u462.png" width="48" heihgt="48" />
</div>

        