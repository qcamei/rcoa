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
        /* @var $authManager RbacManager*/
        $authManager = Yii::$app->authManager;
        $isMe = false;
        $buttonName = '';
        $url = '';
        $params = [];
        $btnClass = 'btn btn-block';
        /* @var $model ShootBookdetail */
        //新建任务
        $isNew = $model->getIsNew();
        //非新建及锁定任务
        $isValid = $model->getIsValid();
        //是否预约中
        $isBooking = $model->getIsBooking();
        //是否在【待指派】任务
        $isAssign = $model->u_shoot_man == null;
        //是否待评价
        $isStausShootIng = $model->getIsStausShootIng();
        //是否失约
        $isBreakPromise = $model->getIsStatusBreakPromise();
        //预约任务时间
        $bookTime = date('Y-m-d H:i:s',$model->book_time);
        //date('d')+1 明天预约时间
        $dayTomorrow = date('Y-m-d H:i:s',strtotime("+1 days"));
        //30天后预约时间
        $dayEnd = date('Y-m-d H:i:s',strtotime("+31 days"));
        
        $url = ($isNew || $isBooking) ? 'create' :'view';
        $params = ($isNew || $isBooking) ? [
            'b_id' => $model->id,
            'site_id' => $model->site_id,
            'book_time' => $model->book_time,
            'index' => $model->index
        ] : ['id' => $model->id];
        if($dayTomorrow < $bookTime && $bookTime < $dayEnd)
            $buttonName = ($isNew || !$isValid) || $isAssign ? '预约' : $model->getStatusName();
        else 
            $buttonName = $isNew || !$isValid ? '未预约' : $model->getStatusName();
        
        $btnClass .= (!$isMe && $isBooking) ? ' disabled' : "";
        
        //摄影组长
        if($authManager->isRole(RbacName::ROLE_SHOOT_LEADER, Yii::$app->user->id))
        {   
            $isMe = !$isNew && ($model->u_shoot_man && $model->shootMan->id == Yii::$app->user->id);
            $url;
            $params;
            $buttonName;
            if($dayTomorrow < $bookTime && $bookTime < $dayEnd)
                $btnClass .= ($isBreakPromise ? ' btn-danger' : 
                    (($isAssign && $isValid) ? ' btn-primary' : ' btn-default'));
            else
                $btnClass .= ($isBreakPromise ? ' btn-danger' : 
                    ($isNew ? ' btn-default disabled' : 
                        ($isStausShootIng ? (!$isMe ? ' btn-default' :' btn-info' ): ' btn-primary')));
            
        //摄影师    
        }else if($authManager->isRole(RbacName::ROLE_SHOOT_MAN, Yii::$app->user->id))
        {
            $isMe = !$isNew && ($model->u_shoot_man && $model->shootMan->id == Yii::$app->user->id);
            $url;
            $params;
            $buttonName;
            if($dayTomorrow < $bookTime && $bookTime < $dayEnd)
                $btnClass .= ($isBreakPromise ? ' btn-danger' : 
                     (($isAssign && $isValid) ? ' btn-primary' : ' btn-default'));
            else
                $btnClass .= ($isBreakPromise ? ' btn-danger' : 
                    ($isNew ? ' btn-default disabled' : 
                        ($isStausShootIng && $isMe ? ' btn-info' : ' btn-default')));
            
        }
        //编导
        else if($authManager->isRole(RbacName::ROLE_CONTACT, Yii::$app->user->id))
        {
            $isMe = !$isNew && ($model->u_booker == Yii::$app->user->id ||  $model->u_contacter == Yii::$app->user->id );
            $url;
            $params;
            $buttonName;
            if($dayTomorrow < $bookTime && $bookTime < $dayEnd)
                $btnClass .= ($isBreakPromise ? ' btn-danger' : 
                     (($isNew) ? ' btn-primary' : ' btn-default'));
            else
                $btnClass .= ($isBreakPromise ? ' btn-danger' : 
                    ($isNew ? ' btn-primary disabled' : 
                        ($isStausShootIng && $isMe ? ' btn-info' : ' btn-default')));
        }
        
        $html = '';
        $html .= '<span class="rcoa-icon rcoa-icon-me is-me ' . ($isMe ? '' : 'hide') . '"/>';
        return $html . Html::a($buttonName, 
                                //如果出现  disabled 样式则删除href 属性,主要是禁用ie浏览器点击
                                strpos($btnClass,' disabled') ? null : Url::to(ArrayHelper::merge([$url], $params,$this->params)), 
                                ['class' => $btnClass, 'role' => "button"]) . '';
    }
    
}
