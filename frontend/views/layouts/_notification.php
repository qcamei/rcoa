<?php

use common\config\AppGlobalVariables;
use common\models\System;
use common\wskeee\job\JobManager;
use common\wskeee\job\models\Job;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* @var $jobManager JobManager */
$jobManager = Yii::$app->get('jobManager');
$notification = $jobManager->getUnReadyNotification(Yii::$app->user->id);

if(!empty($notification)){
    $unReadyNotice = $jobManager->getNotificationInfo(ArrayHelper::getColumn($notification, 'job_id'), ArrayHelper::getColumn($system, 'id'));
    $notice = ArrayHelper::getColumn($unReadyNotice, 'system_id');
} else {
    $unReadyNotice = [];
    $notice = [];
}
?>
<span class="badge badge-warning"><?php echo count($notification)?></span>
<ul class="dropdown-menu extended notification">
    <li>
        <p id="text">你总有<?php echo count($notification)?>个通知</p>
    </li>
    
        <div class="job-notice-list">
        <?php 
            foreach ($system as $value) {
                if(!in_array($value->id, $notice)) continue;
                echo '<li>';
                echo '<p>【'.$value->name.'】</p>';   
                echo '</li>';
                foreach ($unReadyNotice as $values) {
                    if($values->system_id != $value->id) continue;
                    echo '<li>';
                    echo Html::a('<span>【'.$values->status.'】</span>'.$values->subject, [$values->link]);
                    echo '</li>';
                }
            }
        ?>
        </div>
    <li>
        <?= Html::a('全部清除', '', ['id'=>'allRemove','style'=>'text-align: center']) ?>
    </li>
</ul>

<?php  
 $js =   
<<<JS
    $("#allRemove").click(function(){
       hasReday();  
    });
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 

<script type="text/javascript">
function hasReday(){
    
    $.post("/job/default/has-ready",function(data)
    {
        /** 是否正常请求 */
        if(data["result"] == 0)
        {
            console.warn("请求失败...！");
            return;
        }
    });
}
</script>