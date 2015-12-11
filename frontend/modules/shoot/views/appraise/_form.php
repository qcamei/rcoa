<?php

use common\models\question\QuestionOp;
use common\models\shoot\ShootAppraise;
use common\models\shoot\ShootAppraiseResult;
use common\models\shoot\ShootBookdetail;
use frontend\modules\shoot\ShootAsset;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use frontend\modules\shoot\components\ShootAppraiseStar;
/* @var $this View */
/* @var $model ShootAppraise */
/* @var $form ActiveForm */
?>

<div class="shoot-appraise-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'shoot-appraise-form',
                'action' => '/shoot/appraise/add',
                'options' =>
                [
                    'class' => 'appraise-form',
                ]
    ]);
    ?>

    <?php 
            
        if(count($appraises) == 0)
        {
            echo "<h2>未找评价题目！</h2>";
        }else
        {   
            /* @var $appraise ShootAppraise */
            /* @var $bookdetail ShootBookdetail */
            /* @var $authManager RbacManager */
            
            $authManager = Yii::$app->authManager;
            $user = Yii::$app->user;
            
            $results = ArrayHelper::index($results, function($result){
                /* @var $result ShootAppraiseResult */
                return $result->role_name.'-'.$result->q_id;
            });
            /* 显示答题情况 */
            $value_result = $bookdetail->getAppraiseInfo();
            
            /** 获取评价结果 */
            $appInfo = $value_result;
            //生成【接洽人】【摄影师】评价图标
            $contacterGood = $appInfo[RbacName::ROLE_CONTACT]['hasDo'] ? ShootAppraiseStar::widget([
                'value'=>$appInfo[RbacName::ROLE_CONTACT]['sum']/$appInfo[RbacName::ROLE_CONTACT]['all']
                    ]) : "" ;
            $shootManGood = $appInfo[RbacName::ROLE_SHOOT_MAN]['hasDo'] ?  ShootAppraiseStar::widget([
                'value'=>$appInfo[RbacName::ROLE_SHOOT_MAN]['sum']/$appInfo[RbacName::ROLE_SHOOT_MAN]['all']
                    ]) : "";
            foreach($appraises as $role_name => $appraise_arr)
            {
                if($role_name!= RbacName::ROLE_CONTACT && $role_name != RbacName::ROLE_SHOOT_MAN)
                    continue;
                $disabled = !(
                        ($bookdetail->u_contacter == $user->id && $role_name != RbacName::ROLE_CONTACT) || 
                        ($bookdetail->u_shoot_man == $user->id && $role_name != RbacName::ROLE_SHOOT_MAN));
                $has_do = $value_result[$role_name]['hasDo'];
                $icon = $role_name == RbacName::ROLE_CONTACT ? $contacterGood : $shootManGood ;
                echo '<h4>'.Html::label($appraise_arr[0]->role->description.$icon).'</h4>';
                foreach($appraise_arr as $index => $appraise)
                {
                    $items = ArrayHelper::map($appraise->question->ops, 'value', function($op){
                        /* @var $op QuestionOp */
                        return $op->value."分 ( $op->title )";
                    });
                    echo Html::label(($index+1).'、'.$appraise->question->title);
                    echo Html::radioList(
                            "$appraise->role_name-$appraise->q_id",
                            getAppraiseResultValue($results,$appraise), 
                            $items,
                            [
                                'class'=>'form-group',
                                'itemOptions' => [
                                    'labelOptions'=>[
                                        'class' =>'radio-group',
                                    ],
                                    'disabled' => ($disabled || $has_do),
                                ],
                                
                            ]);
                }
                
            }
        }
        
        /**
         * 获取题目结果合并名
         * @param ShootAppraise $appraise
         * @return string role_name-q_id
         */
        function getQName($appraise)
        {
            return "$appraise->role_name-$appraise->q_id";
        }
        
        function getAppraiseResultValue($results,$appraise)
        {
            return isset($results[getQName($appraise)]) ? $results[getQName($appraise)]->value : null;
        }
    ?>
    
    <?= Html::hiddenInput('b_id', $b_id) ?>
    
    <?php ActiveForm::end(); ?>

</div>
<?php  
 $js =   
<<<JS
    /**设置radio选中改变颜色*/
    $("input:radio:checked").parent().css("color","#2E6DA4");  
    $("input:radio").change(function (){   
        $("input:radio:not([checked])[name="+this.name+"]").parent().css("color","#000000");  
        $("input:radio:checked[name="+this.name+"]").parent().css("color","#2E6DA4");  
    });  
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 
<?php ShootAsset::register($this) ?>
