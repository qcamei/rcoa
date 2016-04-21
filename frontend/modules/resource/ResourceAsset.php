<?php


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace frontend\modules\resource;

use yii\web\AssetBundle;
class ResourceAsset extends AssetBundle
{
    //public $basePath = '@webroot/assets';
    //public $baseUrl = '@web/assets';
    public $sourcePath = '@frontend/modules/resource/assets';
    public $css = [
       'css/resource.css'
    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
}