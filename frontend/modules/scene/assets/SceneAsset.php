<?php
namespace frontend\modules\scene\assets;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use yii\web\AssetBundle;
/**
 * Description of RbacAsset
 *
 * @author Administrator
 */
class SceneAsset extends AssetBundle
{
    //public $basePath = '@webroot/assets';
    //public $baseUrl = '@web/assets';
    public $sourcePath = '@frontend/modules/scene/assets';
    public $css = [
       'css/style.css',
       'css/layout.css',
       'css/module.css',
    ];
    public $js = [
        'js/jquery.raty.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
}
