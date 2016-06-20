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
       //'css/default.css',
       //'css/component.css',
    ];
    public $js = [
        'js/ueditor/ueditor.config.js',
        'js/ueditor/ueditor.all.js',
        'js/classie.js',
        'http://eefile.gzedu.com/js/lhgdialog.min.js',
        'http://eefile.gzedu.com/js/json2.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
   
}
