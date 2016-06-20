<?php

use wskeee\filemanage\models\FileManage;
use yii\helpers\Html;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/** title */
echo Html::beginTag('div', ['class'=>'title']);
    echo Html::beginTag('div', ['class'=>'container']);
        echo Html::beginTag('ul',['class' => 'breadcrumbs', 'style' => 'padding-left: 10px']);
            echo Html::beginTag('li', ['class' => 'course-name']);
                echo Html::a('首页', ['index']);
            echo Html::endTag('li');
            if(!isset($bread) || empty($bread)) echo '<li class="disabled course-name">没有相关目录</li>';

            foreach ($bread as $key => $value) {
                echo Html::beginTag('li',['class' => 'course-name', 'style' => $value->type != FileManage::FM_FILE ? :'width:30%;']);
                    echo Html::a($value->name, [$value->type != FileManage::FM_FOLDER ? 'view' :'index', 'id' => $value->id],
                         ['class' => (!isset($get['id'])? null : $get['id']) != $value->id ?  '' : 'disabled']);
                echo Html::endTag('li');
            }
        echo Html::endTag('ul');
    echo Html::endTag('div');
echo Html::endTag('div');