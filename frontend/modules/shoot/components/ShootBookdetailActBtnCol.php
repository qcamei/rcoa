<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace frontend\modules\shoot\components;
use \Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use wskeee\rbac\RbacName;
use wskeee\rbac\RbacManager;

use common\models\shoot\ShootBookdetail;
/**
 * Description of ShootBookdetailActBtnCol
 *
 * @author Administrator
 */
class ShootBookdetailActBtnCol extends ShootBookdetailListTd 
{
    public $params = [];
    
    public function init() {
        parent::init();
        $this->format = 'html';
    }
    //put your code here
    public function getDataCellValue($model, $key, $index) 
    {
        $isMe = false;
        $buttonName = '';
        $url = '';
        $params = [];
        $btnClass = 'btn btn-block';
        /* @var $model ShootBookdetail */
        /* @var $authManager RbacManager*/
        $authManager = Yii::$app->authManager;
        //新建任务
        $isNew = $model->getIsNew();
        //非新建及锁定任务
        $isValid = $model->getIsValid();
        //是否在【待指派】任务
        $isAssign = $model->u_shoot_man == null;
        //是否失约
        $isBreakPromise = $model->getIsStatusBreakPromise();
        //摄影组长
        if($authManager->isRole(RbacName::ROLE_SHOOT_LEADER, Yii::$app->user->id))
        {
            $buttonName = !$isValid ? '未预约' : ($isAssign ? $model->getStatusName() : $model->getStatusName() );
            
            $url = 'view';
            $params = [
                'id' => $model->id
            ];
            $btnClass .= ($isBreakPromise ? ' btn-danger' : (($isAssign && $isValid) ? ' btn-primary' : ' btn-default'));
            $btnClass .= (!$isValid ? ' disabled' : '');
        //摄影师    
        }else if($authManager->isRole(RbacName::ROLE_SHOOT_MAN, Yii::$app->user->id))
        {
            $buttonName = !$isValid ? '未预约' :($isAssign ? $model->getStatusName() : $model->getStatusName());
            $url = 'view';
            $params = [
                'id' => $model->id
            ];
            $btnClass .= $isBreakPromise ? ' btn-danger' : ' btn-default';
            $btnClass .= ($isNew ? ' disabled' : '');
            $isMe = (!$isNew && $model->u_shoot_man && $model->shootMan->id == Yii::$app->user->id);
        }
        //编导
        else if($authManager->isRole(RbacName::ROLE_CONTACT, Yii::$app->user->id))
        {
            //预约任务时间
            $bookTime = date('Y-m-d H:i:s',$model->book_time);
            //date('d')+1 明天预约时间
            $dayTomorrow = date('Y-m-d H:i:s',strtotime("+1 days"));
            //30天后预约时间
            $dayEnd = date('Y-m-d H:i:s',strtotime("+31 days"));
            if($dayTomorrow < $bookTime && $bookTime < $dayEnd){
                $buttonName = $isNew  ? '预约' : (!$isValid ? '预约中' : $model->getStatusName());
            }else{
                $buttonName = $isNew ? '未预约' :(!$isValid ? '预约中' : $model->getStatusName());
                
            }
            $url = ($isNew || $model->getIsBooking()) ? 'create' : 'view';
            $params = ($isNew || $model->getIsBooking()) ? 
                    [
                        'b_id' => $model->id,
                        'site_id' => $model->site_id,
                        'book_time' => $model->book_time,
                        'index' => $model->index
                    ] : ['id' => $model->id];
            $isMe = !$isNew && ($model->booker->id || $model->contacter->id) == Yii::$app->user->id;
            if($dayTomorrow < $bookTime && $bookTime < $dayEnd){
                $btnClass .= ($isBreakPromise ? ' btn-danger' : ($isNew ? ' btn-primary' : ' btn-default'));
            }else{
                $btnClass .= ($isBreakPromise ? ' btn-danger' : ($isNew ? ' btn-primary disabled' : ' btn-default'));
            }
            $btnClass .= (!$isMe && $model->getIsBooking()) ? ' disabled' : "";
        }
        $html = '';
        $html .= '<span class="rcoa-icon rcoa-icon-me is-me ' . ($isMe ? '' : 'hide') . '"/>';
        return $html . Html::a($buttonName, 
                                //如果出现  disabled 样式则删除href 属性,主要是禁用ie浏览器点击
                                strpos($btnClass,' disabled') ? null : Url::to(ArrayHelper::merge([$url], $params,$this->params)), 
                                ['class' => $btnClass, 'role' => "button"]) . '';
    }
}
