<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace wskeee\notification;

use Yii;
use yii\web\View;

/**
 * Description of NotificationMailManager
 *
 * @author Administrator
 */
class NotificationMailManager {
    //put your code here
    /** view默认位置 */
    public static $viewPath = '@common/mail/';

    /** 定义应用$agent_id常量 = 1000007 */
    public static $agent_id = 1000007;

    /**
     * send 发送信息的函数
     * @param string|array $receivers       接收者，以‘|’分隔，包含中文需使用URL编码
     * @param string $title                 消息标题
     * @param string $url                   访问链接
     * @param string $content               消息内容
     */
    public static function send($receivers, $title, $url, $content) 
    {
        if (is_array($receivers))
            $receivers = implode('|', $receivers);

        //发送邮件消息 
        $mail = Yii::$app->mailer->compose()
                ->setTo($receivers)
                ->setSubject($title)
                ->setHtmlBody($content)
                ->send();
        
        return $mail;
    }

    /**
     * 用视图模板渲染send
     * @param string $view                  视图模板
     * @param string $params                转进视图模板参数
     * @param string|array $receivers       接收者，以‘|’分隔，包含中文需使用URL编码
     * @param string $title                 消息标题
     * @param string $url                   访问链接
     * @return type
     */
    public static function sendByView($view, $params, $receivers, $title = '', $url = '') 
    {
        /** 用于渲染模板 */
        $render = new View();

        //$url = self::$viewPath;
        if (strpos($view, '@') == false)
            $view = self::$viewPath . $view;

        return self::send(
                $receivers, 
                $title,
                $url,
                $render->render($view, array_merge($params,['link' => $url]))
        );
    }
}
