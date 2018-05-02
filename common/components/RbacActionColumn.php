<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

use Exception;
use wskeee\rbac\components\ResourceHelper;
use Yii;
use yii\db\ActiveRecordInterface;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Description of ActionColumn
 *
 * @author Administrator
 */
class RbacActionColumn extends ActionColumn{
    
    /**
     * 自定义跳转控制器
     * @var string 
     */
    public $customController;
    /**
     * 按钮附加参数
     * [
     *  'view' => ['aa' => 1],
     *  'update' => ['id' = >22],
     *  'delete' => ['callback' => 'aaa'],
     * ]
     * @var array 
     */
    public $buttonUrlParams;
    /**
     * Creates a URL for the given action and model.
     * This method is called for each button and each row.
     * @param string $action the button name (or action ID)
     * @param ActiveRecordInterface $model the data model
     * @param mixed $key the key associated with the data model
     * @param int $index the current row index
     * @return string the created URL
     */
    public function createUrl($action, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index, $this);
        }

        $params = is_array($key) ? $key : ['id' => (string) $key];
        //添加按钮附加参数
        if(isset($this->buttonUrlParams) && isset($this->buttonUrlParams[$action])){
            if(!is_array($this->buttonUrlParams[$action])){
                throw new Exception('按钮附加参数必须为 array');
            }
            $params = array_merge($params,$this->buttonUrlParams[$action]);
        }
        
        //重定义跳转控制器
        if(isset($this->customController)){
            $params[0] = $this->customController . '/' . $action;
        }else
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

        return Url::toRoute($params);
    }
    
    //put your code here
    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = Yii::t('yii', 'View');
                        break;
                    case 'update':
                        $title = Yii::t('yii', 'Update');
                        break;
                    case 'delete':
                        $title = Yii::t('yii', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $additionalOptions, $this->buttonOptions);
                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                return ResourceHelper::a($icon, $url, $options);
            };
        }
    }
}
