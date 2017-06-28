<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\worksystem\components;

use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Description of GroupGridViewDataColumn
 *
 * @author Administrator
 */
class GridViewLinkCell extends DataColumn {
    
    public $url;
    /* 主要参数 */
    public $key = 'id';
    public $params = [];
    
    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if(isset($this->url))
        {
            return Html::tag('a', parent::renderDataCellContent($model, $key, $index), [
                'href'=>  Url::to(array_merge([$this->url,  'id'=>ArrayHelper::getValue($model, $this->key)],$this->params))
            ]);
        }else
            return parent::renderDataCellContent($model, $key, $index);
    }
}
