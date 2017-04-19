<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\widgets\cslider;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Description of CSliderAssets
 *
 * @author Administrator
 */
class CSliderAssets extends AssetBundle {
    //put your code here
    public $sourcePath = __DIR__ . '/assets';
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    public $publishOptions = [
        'forceCopy'=>YII_DEBUG
    ];     
    public $css = [
       'css/cslider.css',
    ];
    public $js = [
        'js/cslider.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
