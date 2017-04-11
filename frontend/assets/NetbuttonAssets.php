<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Description of BasedataAssets
 *
 * @author Administrator
 */
class NetbuttonAssets extends AssetBundle{
    //put your code here
    //public $sourcePath = '@frontend/modules/demand/assets';
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $publishOptions = [
        'forceCopy'=>YII_DEBUG
    ];     
    public $css = [
       
    ];
    public $js = [
        'filedata/site/netbutton/createjs-2015.11.26.min.js',
        'filedata/site/netbutton/netbutton.js',
        'filedata/site/netbutton/netbuttonskin.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
