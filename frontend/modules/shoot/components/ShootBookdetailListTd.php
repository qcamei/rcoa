<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace frontend\modules\shoot\components;

use yii\bootstrap\Html;
use yii\grid\DataColumn;
/**
 * Description of ShootBookdetailListTd
 *
 * @author Administrator
 */
class ShootBookdetailListTd extends DataColumn{
    public function renderDataCell($model, $key, $index) {
        if($index%6 <3)
            Html::addCssClass ($this->contentOptions, 'bgcolor-zebra');
        else
            Html::removeCssClass ($this->contentOptions, 'bgcolor-zebra');
        return parent::renderDataCell($model, $key, $index);
    }
}
