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
        echo Html::a('<i class="'.$value->icon.'" '
                . 'style="'.($value->type == FileManage::FM_LIST ? :'color:#ccc').'"></i>'
                .$value->name, [$value->type == FileManage::FM_FILE ? 'view' :'index', 'id' => $value->id],['class' => 'course-name']);
    }
echo Html::endTag('div');