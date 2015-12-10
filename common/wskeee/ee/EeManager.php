<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace wskeee\ee;

use linslin\yii2\curl\Curl;
use yii\web\View;

/**
 * Description of EeManager
 *
 * @author Administrator
 */
class EeManager {
    /** ee通知地址 */
    public static $url = 'http://im.eecn.cn:8221/FocuIMManage/httpservices/msg-sendMessageToUsers.action';
    /** view默认位置 */
    public static $viewPath = '@common/mail/';
    
    /**
     * 
     * @param string|array $receivers       接收者，以逗号分隔，包含中文需使用URL编码
     * @param string $title                 消息标题
     * @param string $content               消息内容
     * @param int $position                 显示位置,0表示居中，1表示右下角(默认0)
     * @param int $width                    窗体宽度
     * @param int $height                   窗体高度
     * @param int $closeDelay               关闭延迟 默认为30秒 +(v1.1)
     * @param string $sender                发送者 为空则无发送者，客户无回复按钮+(v1.1)
     */
    public static function seedEe($receivers,$title,$content,$position=1,$width=400,$height=300,$closeDelay=99999999,$sender='')
    {
        if(is_array($receivers))
            $receivers = implode(',', $receivers);
        
        $curl = new Curl();
        $response = $curl->reset()
                ->setOption(CURLOPT_RETURNTRANSFER,true)
                ->setOption(
                    CURLOPT_POSTFIELDS,
                    http_build_query([
                        'receivers' => $receivers,
                        'title' => $title,
                        'content' => $content,
                        'position' => $position,
                        'width' => $width,
                        'height' => $height,
                        'closeDelay' => $closeDelay,
                    ]))
                ->post(self::$url);
        return $curl->response;
    }
    
    /**
     * 
     * @param string $view                  视图模板
     * @param array $params                 转进视图模板参数
     * @param string|array $receivers       接收者，以逗号分隔，包含中文需使用URL编码
     * @param string $title                 消息标题
     * @param int $position                 显示位置,0表示居中，1表示右下角(默认0)
     * @param int $width                    窗体宽度
     * @param int $height                   窗体高度
     * @param int $closeDelay               关闭延迟 默认为30秒 +(v1.1)
     * @param string $sender                发送者 为空则无发送者，客户无回复按钮+(v1.1)
     */
    public static function sendEeByView($view,$params,$receivers,$title='',$position=1,$width=400,$height=300,$closeDelay=99999999,$sender='')
    {
        /** 用于渲染模板 */
        $render = new View();
        
        $url = self::$viewPath;
        if(strpos($view, '@')==false)
            $view = self::$viewPath.$view;
        
        return self::seedEe(
                $receivers, 
                $title, 
                $render->render($view,$params), 
                $position, 
                $width, 
                $height, 
                $closeDelay, 
                $sender);
    }
}
