<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace wskeee\notification;

use wskeee\notification\core\AppApi;
use yii\web\View;

class NotificationManager {

    /** view默认位置 */
    public static $viewPath = '@common/mail/';
    public static $agent_id = 1000007;

    /**
     * 
     * @param string|array $receivers       接收者，以‘|’分隔，包含中文需使用URL编码
     * @param string $title                 消息标题
     * @param string $content               消息内容
     * @return string
     */
    public static function send($receivers, $title, $content) {

        if (is_array($receivers))
            $receivers = implode('|', $receivers);

        $msg = array(
            'touser' => $receivers,
            'toparty' => '',
            'msgtype' => 'text',
            'agentid' => self::$agent_id,
            'text' => array(
                "content" => $content)
        );

        $api = new AppApi(self::$agent_id);
        $api->sendMsgToUser($msg);
    }

    public static function sendByView($view, $params, $receivers, $title = '') {
        /** 用于渲染模板 */
        $render = new View();

        $url = self::$viewPath;
        if (strpos($view, '@') == false)
            $view = self::$viewPath . $view;

        return self::send(
                $receivers,
                $title,
                $render->render($view, $params)
        );
    }

}
