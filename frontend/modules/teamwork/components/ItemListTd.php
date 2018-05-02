<?php

namespace frontend\modules\teamwork\components;

use common\models\teamwork\ItemManage;
use yii\bootstrap\Html;
use yii\grid\DataColumn;




/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ItemListTd extends DataColumn{
    /* @var $model ItemManage */
    public function renderDataCell($model, $key, $index) {
        if($index%2 < 3)
            Html::addCssClass ($this->contentOptions, 'bgcolor-zebra');
        else
            Html::removeCssClass ($this->contentOptions, 'bgcolor-zebra');
        return parent::renderDataCell($model, $key, $index);
    }
}