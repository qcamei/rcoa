<?php

namespace backend\modules\unittest\controllers;

use wskeee\ee\EeManager;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `unittest` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
       
        return $this->render('index');
    }
    
    /**
     * 打印php
     */
    public function actionPhpinfo(){
        phpinfo();exit;
    }
    /**
     * 邮件测试
     */
    public function actionMailTest(){
        //主题 
        $subject = "邮件测试！";
        //查找所有摄影组长
        
        //所有摄影师组长ee
        $receivers_ee = '101463731,101463735';
       
        //所有摄影师组长邮箱地址
        $receivers_mail = ['heyangchao@eenet.com','wskeee@163.com'];
         //var_dump($receivers_mail);exit;
        //发送ee消息 
        EeManager::seedEe($receivers_ee,$subject,'ee测试，测试！');
        
        var_dump(\Yii::$app->mailer);
        //发送邮件消息 
        $mail = Yii::$app->mailer->compose()
        ->setTo($receivers_mail)
        ->setSubject($subject)
        ->setTextBody('邮件测试！！！')
        ->send();
        echo $mail ? '成功！': '失败！';
    }
}
