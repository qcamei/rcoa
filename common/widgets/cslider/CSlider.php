<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\widgets\cslider;

use yii\base\Widget;
use yii\web\View;

/**
 * Description of CSlider
 *
 * @author Administrator
 */
class CSlider extends Widget{
    
    public $plugName = 'ccoa_Widgets_CSlider';
    
    /**
     *  
     *  'max' => 1,                 //最大值
        'height' => 10,
        'value' => 0,
        'valueText' => null,        //指定显示文字
        'trackColor' => '#ddd',     //滑动条底色块
        'sliderColor' => '#428BCA', //已选择颜色
        'tooltipColor' => '#000',   //提示颜色
     * @var type 
     */
    public $plugOptions = [];
    
    public function init(){
        parent::init();
    }
    //put your code here
    public function run(){
        parent::run();
        
        $id = $this->plugName.rand(0, 10000);
        $this->plugOptions['id'] = $id;
        $ops = json_encode($this->plugOptions);
        $script = "new window.{$this->plugName}({$ops});";
        $view = $this->getView();
        $view->registerJs($script, View::POS_LOAD);
        CSliderAssets::register($view);
        return "<div id=\"{$id}\"></div>";
    }
}
