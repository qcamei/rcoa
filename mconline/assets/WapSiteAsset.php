<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace mconline\assets;
use const YII_DEBUG;
/**
 * Description of RbacAsset
 *
 * @author Administrator
 */
class WapSiteAsset extends \yii\web\AssetBundle
{
    //public $basePath = '@webroot/assets';
    //public $baseUrl = '@web/assets';
    public $sourcePath = '@mconline/assets';
    public $css = [
       'css/wap_site.css',
    ];
    public $js = [
        
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
