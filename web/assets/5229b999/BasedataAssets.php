<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\demand\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Description of BasedataAssets
 *
 * @author Administrator
 */
class BasedataAssets extends AssetBundle{
     //put your code here
    public $sourcePath = '@frontend/modules/demand/assets';
    public $publishOptions = [
        'forceCopy'=>YII_DEBUG
    ];  
    public $css = [
       'css/basedata.css'
    ];
    public $js = [
        
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}
