<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\scene\assets;

use yii\web\AssetBundle;

/**
 * Description of RbacAsset
 *
 * @author Administrator
 */
class FooterAsset extends AssetBundle
{
    public $sourcePath = '@frontend/modules/scene/assets';
    public $depends = [
        'yii\web\YiiAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
    public $css = [
       'css/_footer.css',
    ];
    public $js = [
        
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
