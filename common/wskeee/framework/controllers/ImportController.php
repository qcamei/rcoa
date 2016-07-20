<?php

namespace wskeee\framework\controllers;

class ImportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    /**
     * 上传文件自动导入
     */
    public function actionUpload(){
        
    }
}
