<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\teamwork\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Description of TwStatisticsAsset
 *
 * @author Administrator
 */
class TwStatisticsAsset extends AssetBundle {
    //put your code here
    public $sourcePath = '@frontend/modules/teamwork/assets';
    public $publishOptions = [
        'forceCopy'=>YII_DEBUG
    ];  
    public $css = [
       'css/statistics.css'
    ];
    public $js = [
        'js/echarts.min.js',
        'js/statistics.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
