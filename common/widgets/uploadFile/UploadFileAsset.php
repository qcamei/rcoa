<?php

namespace common\widgets\uploadFile;

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
class UploadFileAsset extends AssetBundle
{
    //public $basePath = '@webroot/assets';
    //public $baseUrl = '@web/assets';
    public $sourcePath = '@common/wskeee/filemanage/assets';
    public $css = [
    ];
    public $js = [
         'http://eefile.gzedu.com/js/lhgdialog.min.js',
        'http://eefile.gzedu.com/js/json2.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
   
}
