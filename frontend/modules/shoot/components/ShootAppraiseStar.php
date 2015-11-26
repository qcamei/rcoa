<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\shoot\components;

use yii\base\Widget;

/**
 * Description of ShootAppraiseStar
 *
 * @author Administrator
 */
class ShootAppraiseStar extends Widget {
    
    /** 
     *  value = 0~1
     *  value = 1 高兴
     *  value >=1/2 失望
     *  value <1/2  大哭 
     */
    public $value;
    
    public function run() 
    {
        $icon = '';
        if ($this->value == 1)
            $icon.='happy';
        else if ($this->value >= 1 / 2)
            $icon.='disappointed';
        else
            $icon.='crying';
        $icon = '<span class="rcoa-icon rcoa-icon-' . $icon . '"></span>';

        return $icon;
    }
}
