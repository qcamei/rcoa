<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\wskeee\framework\assets;

use yii\web\AssetBundle;
use const YII_DEBUG;

/**
 * Description of FrameworkImportAsset
 *
 * @author Administrator
 */
class FrameworkImportAsset extends AssetBundle{
    public $sourcePath = '@common/wskeee/framework/assets';
    public $css = [
       'css/import.css',
    ];
    public $js = [
        'js/import.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
