<?php
namespace frontend\modules\expert;

use yii\web\AssetBundle;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ExpertAsset extends AssetBundle
{
    //public $basePath = '@webroot/assets';
    //public $baseUrl = '@web/assets';
    public $sourcePath = '@frontend/modules/expert/assets';
    public $css = [
       'css/expert.css'
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