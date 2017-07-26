<?php

namespace frontend\modules\worksystem\utils;

use yii\helpers\Html;


class WorksystemOperationHtml
{
    private static $instance = null;
    
    public function getOperationTypeHtml($controllerAction, $content)
    {
        switch ($controllerAction)
        {
            case 'task/create-check':
                if(is_numeric($content)){
                    if($content == 1)
                        echo '<td class="course-name" style="padding: 4px 8px;">'.Html::button('审核通过', ['class' => 'btn btn-success btn-sm']).'</td>';
                    else
                        echo '<td class="course-name" style="padding: 4px 8px;">'.Html::button('审核不通过', ['class' => 'btn btn-danger btn-sm']).'</td>';
                }else
                    echo '<td class="course-name">'.$content.'</td>';
                break;  
            case 'task/create-acceptance':
                if(is_numeric($content)){
                    if($content == 1)
                        echo '<td class="course-name" style="padding: 4px 8px;">'.Html::button('验收通过', ['class' => 'btn btn-success btn-sm']).'</td>';
                    else
                        echo '<td class="course-name" style="padding: 4px 8px;">'.Html::button('验收不通过', ['class' => 'btn btn-danger btn-sm']).'</td>';
                }else
                    echo '<td class="course-name">'.$content.'</td>';
                break;
            case 'task/complete-acceptance':
                if(is_numeric($content)){
                    if($content == 1)
                        echo '<td class="course-name" style="padding: 4px 8px;">'.Html::button('验收通过', ['class' => 'btn btn-success btn-sm']).'</td>';
                    else
                        echo '<td class="course-name" style="padding: 4px 8px;">'.Html::button('验收不通过', ['class' => 'btn btn-danger btn-sm']).'</td>';
                }else
                    echo '<td class="course-name">'.$content.'</td>';
                break;
            default:
                echo '<td class="course-name">'.$content.'</td>';
        }
    }

    /**
     * 获取单例
     * @return WorksystemOperationHtml
     */
    public static function getInstance() 
    {
        if (self::$instance == null) {
            self::$instance = new WorksystemOperationHtml();
        }
        return self::$instance;
    }
}
