<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\shoot\components;

use yii\base\Widget;
use frontend\modules\shoot\components\ShootBookdetailListTd;
/**
 * Description of ShootWeekRowItem
 *
 * @author Administrator
 */
class ShootBookdetailListWeekTd extends ShootBookdetailListTd{
    public function renderDataCell($model, $key, $index) {
        //$this->contentOptions["rowspan"] = 2;
        if(isset($this->contentOptions["rowspan"]))
        {
            $rowspan = $this->contentOptions["rowspan"];
            var_dump($rowspan);exit;
            if($index%$rowspan == 0)
                return parent::renderDataCell($model, $key, $index);
            else
                return null;
        }else
            return parent::renderDataCell($model, $key, $index);
    }
}
