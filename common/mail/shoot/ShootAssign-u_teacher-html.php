<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\shoot\ShootBookdetail;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
     
 */
?>
<div class="mail-new-shoot">
    <p><?= Html::encode($model->teacher->nickname)?>老师：</p>
    
    <p>您好！我是负责您<b><?= Html::encode($model->fwCourse->name)?></b>的教学编导<b><?= Html::encode($model->booker->nickname) ?></b>，现与您确定课程拍摄时间为 </p>

    <p><b><?= Html::encode($bookTime) ?> <?= Html::encode($model->start_time) ?></b>，地点为<b><?= Html::encode($model->site->des) ?></b>，</p>
    
    <p>届时请到6号楼6楼前台找我，再一同前往拍摄，如有变动，请提前一天以上通知我改约时间。 </p>
    
    <p>拍摄要求：请提前熟悉PPT，避免现场修改，提高拍摄效率。</p>
    
    <p>着装要求：男士请穿深色正装和皮鞋，衣服和领带避免蓝色，不能有条纹以及斑点，以免影响拍摄效果。</p>
    
    <p>女士请化淡妆，穿正装，颜色和花纹不宜太过花哨，颜色避免蓝色，不能有条纹以及斑点，衣领开口勿过低，需有腰带用来佩戴无线麦。</p>
    
    <p>以上着装，如觉穿着不舒服，可以带来现场替换，拍摄完毕再换回普通衣服。 </p>
    
    <p>如果问题，请与我联系，谢谢！联系电话：<b><?= Html::encode($model->booker->phone) ?></b></p>
    
</div>