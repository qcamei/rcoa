<?php

namespace common\wskeee\filemanage;

use yii\web\AssetBundle;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of RbacAsset
 *
 * @author Administrator
 */
class FileManageAsset extends AssetBundle
{
    //public $basePath = '@webroot/assets';
    //public $baseUrl = '@web/assets';
    public $sourcePath = '@common/wskeee/filemanage/assets';
    public $css = [
       'css/filemanage.css',
    ];
    public $js = [
        'js/classie.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
   
}
