<?php

use wskeee\filemanage\models\FileManage;
use yii\helpers\Html;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 /** 左侧类目 */
echo Html::beginTag('div',['class' => 'cbp-spmenu-vertical']);
    if(!isset($list) || empty($list)) echo "<h4>没有相关目录</h4>";
    foreach ($list as $key => $value) {
        $fileSuffix = pathinfo($value->file_link, PATHINFO_EXTENSION);
        echo Html::a(Html::img([$value->image],['width' => '16', 'height' => '16']).$value->name, 
                $fileSuffix == 'rar' || $fileSuffix == 'zip' ?
                    'http://eefile.gzedu.com'.$value->file_link :
                    [$value->type != FileManage::FM_FOLDER ? 'view' :'index', 'id' => $value->id],
                ['class' => (!isset($get['id'])? null : $get['id']) == $value->id ? 'active course-name' : 'course-name']);
    }
echo Html::endTag('div');

?>
