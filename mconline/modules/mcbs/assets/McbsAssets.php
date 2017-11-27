<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace mconline\modules\mcbs\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Description of DemandAssets
 *
 * @author Administrator
 */
class McbsAssets extends AssetBundle{
    //put your code here
    public $sourcePath = '@mconline/modules/mcbs/assets';
    public $depends = [
        'yii\web\YiiAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
    public $css = [
        'css/style.css',
        'css/layout.css',
        'css/module.css',
    ];
    public $js = [
        'js/html.sortable.min.js',
        'js/renderDom.js'
    ];
}
