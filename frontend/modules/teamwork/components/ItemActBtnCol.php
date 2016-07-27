<?php

namespace frontend\modules\teamwork\components;

use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\components\ItemListTd;
use frontend\modules\teamwork\TeamworkTool;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ItemActBtnCol extends ItemListTd {
    public $params = [];
    
    public function init() {
        parent::init();
        $this->format = 'raw';
    }
    //put your code here
    public function getDataCellValue($model, $key, $index) 
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $controllerId = Yii::$app->controller->id;              //当前控制器
        $actionId = Yii::$app->controller->action->id;      //当前行为方法
        $url = [];          //href
        $html = '';         //html
        $params = [];       //字段
        $buttonName = [];   //按钮名
        $button = [];       //生成按钮
        $btnClass = [];     //按钮样式类名
        /* @var $model ItemManage */
        /* @var $twTool TeamworkTool */
        if (!empty($model) && $controllerId == 'default' && $actionId == 'list'){
            $url = [
                'view' => 'view',
                'course' => 'course/index',
            ];
            $buttonName = [
                'view' => '查看',
                'course' => '课程',  
            ];
            $params = [
                'view' => ['id' => $model->id,],
                'course' => ['project_id' => $model->id,]
            ];
            $btnClass = [
               'view' => 'btn btn-primary',
               'course' => 'btn btn-primary'
            ];
        }
        /* @var $model CourseManage */
        /* @var $twTool TeamworkTool */
        else if (!empty($model) && $controllerId == 'course' && $actionId == 'list' && $twTool->getIsLeader()){
            $url = [
                'update' => 'update',
                'delete' => 'delete',
            ];
            $buttonName = [
                'update' => '修改',
                'delete' => '删除',  
            ];
            $params = [
                'update' => ['id' => $model->id,],
                'delete' => [
                    'id' => $model->id,
                    'project_id' => $model->project_id
                ]
            ];
            $btnClass = [
               'update' => $model->create_by == \Yii::$app->user->id ? 'btn btn-primary' : 'btn btn-primary disabled',
               'delete' => $model->create_by == \Yii::$app->user->id ? 'btn btn-danger' : 'btn btn-danger disabled'
            ];
        }
        /* @var $model CourseManage */
        /* @var $twTool TeamworkTool */
        else if (!empty($model) && $controllerId == 'course' && $actionId == 'index') {
            $url = [
                'view' => 'view',
                'deploy' => 'courselink/index',
                'progress' => 'courselink/progress',
            ];
            $buttonName = [
                'view' => '查看',
                //'deploy' => '配置',
                'progress' => '进度',  
            ];
            $params = [
                'view' => ['id' => $model->id],
                //'deploy' => ['course_id' => $model->id,],
                'progress' => ['course_id' => $model->id,]
            ];
            $btnClass = [
               'view' => 'btn btn-primary',
               //'deploy' => $twTool->getIsLeader() ? 'btn btn-primary' : 'btn btn-primary disabled',
               'progress' => 'btn btn-primary',
            ];
        }
        foreach ($buttonName as $key => $value) {
            $button[] = Html::a($value, 
                    //如果出现  disabled 样式则删除href 属性,主要是禁用ie浏览器点击
                    strpos($btnClass[$key],' disabled') ? null : Url::to(ArrayHelper::merge([$url[$key]], $params[$key], $this->params)),[
                        'class' => $btnClass[$key], 
                        $url[$key] != 'delete' ?  '' : 'data' => ['method' => 'post'],
                        'role' => "button", 'style' => 'margin-right:4px;']) . '';
        }
        
        return  implode('',$button);
    }
}