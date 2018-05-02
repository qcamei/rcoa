<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace mconline\modules\mcbs\assets;
use const YII_DEBUG;
/**
 * Description of RbacAsset
 *
 * @author Administrator
 */
class FooterAsset extends \yii\web\AssetBundle
{
    //public $basePath = '@webroot/assets';
    //public $baseUrl = '@web/assets';
    public $sourcePath = '@mconline/modules/mcbs/assets';
    public $css = [
       'css/_footer.css',
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
