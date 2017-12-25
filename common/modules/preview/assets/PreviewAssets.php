<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\modules\preview\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Description of DemandAssets
 *
 * @author Administrator
 */
class PreviewAssets extends AssetBundle {

    //put your code here
    public $sourcePath = '@common/modules/preview/assets';
    public $depends = [
        'yii\web\YiiAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
    public $css = [
        'css/style.css',
    ];
    public $js = [
        
    ];

}
