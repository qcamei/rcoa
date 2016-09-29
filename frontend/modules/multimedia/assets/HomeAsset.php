<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\multimedia\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Description of HomeAsset
 *
 * @author Administrator
 */
class HomeAsset extends AssetBundle {
    //put your code here
    public $sourcePath = '@frontend/modules/multimedia/assets';
    public $publishOptions = [
        'forceCopy'=>YII_DEBUG
    ];  
    public $css = [
       'css/home.css'
    ];
    public $js = [
        'js/echarts.min.js',
        'js/home-chart.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}