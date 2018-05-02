<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace mconline\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Description of DemandAssets
 *
 * @author Administrator
 */
class SiteAssets extends AssetBundle{
    //put your code here
    public $sourcePath = '@mconline/assets';
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
    public $css = [
        'css/site.css',
    ];
    public $js = [
        
    ];
}
