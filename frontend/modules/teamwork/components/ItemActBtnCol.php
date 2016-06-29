<?php

namespace frontend\modules\teamwork\components;

use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\components\ItemListTd;
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
        $this->format = 'html';
    }
    //put your code here
    public function getDataCellValue($model, $key, $index) 
    {
        //$url = '';
        $html = '';
        $params = [];
        $buttonName = [
            'deploy' => '配置',
            'course' => '课程',
            'progress' => '进度',
            'update' => '修改',
            'delete' => '删除',
        ];
        $btnClass = 'btn ';
        
        /* @var $model ItemManage */
        if (!empty($model)){
            $params = [
                'project_id' => $model->id,
            ];
            $btnClass .= $model->isLeader() ?  'btn-primary' : 'btn-primary disabled';
            $button1 = Html::a($buttonName['deploy'], 
                        //如果出现  disabled 样式则删除href 属性,主要是禁用ie浏览器点击
                        strpos($btnClass,' disabled') ? null : Url::to(ArrayHelper::merge(['/teamwork/course/index'], [], $this->params)), 
                        ['class' => $btnClass, 'role' => "button", 'style' => 'margin-right:8px;']) . ''; 
            $button2 = Html::a($buttonName['course'], 
                        //如果出现  disabled 样式则删除href 属性,主要是禁用ie浏览器点击
                        strpos($btnClass,' disabled') ? null : Url::to(ArrayHelper::merge(['/teamwork/course/create'], $params, $this->params)), 
                        ['class' => $btnClass, 'role' => "button"]) . ''; 
        }
        return  "{$button1}{$button2}";
    }
}