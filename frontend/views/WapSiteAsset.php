<?php
namespace frontend\views;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
    public $sourcePath = '@frontend/views/assets';
    public $css = [
       'css/wap_site.css',
    ];
    public $js = [
        'js/shakeobj.js',
        'js/numberturn.js',
        'js/jquery.easing.1.3.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
