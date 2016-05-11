<?php

use common\config\AppGlobalVariables;
use yii\helpers\Html;
use yii\web\View;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$jobManager = Yii::$app->get('jobManager');
$unReadyNotice = $jobManager->getUnReadyNotification(Yii::$app->user->id);


?>
<span class="badge badge-warning"><?php echo count($unReadyNotice)?></span>
<ul class="dropdown-menu extended notification">
    <li>
        <p id="text">你总有<?php echo count($unReadyNotice)?>个通知</p>
    </li>
    <li>
        <p>【拍摄】</p>
    </li>
    
    <?php 
        foreach ($unReadyNotice as $key=>$value) {
            if($key > 1 ||  $value->system_id != 2) continue;
            echo '<li>';
            echo Html::a('<span>【'.$value->status.'】</span>'.$value->subject, [$value->link]);
            echo '</li>';
        }
        
    ?>
    
    <li>
        <p>【多媒体制作】</p>
    </li>
    
    <?php 
        foreach ($unReadyNotice as $key=>$value) {
            if($key > 2 ||  $value->system_id != 3) continue;
            echo '<li>';
            echo Html::a('<span>【'.$value->status.'】</span>'.$value->subject, [$value->link]);
            echo '</li>';
        }
        
    ?>
    
    <li>
        
        <?= Html::a('全部清除','', ['id'=>'allRemove','style'=>'text-align: center'])?>
        
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
     $.ajax({
        url:'/job/default/has-ready',
        data:{systemId:"<?= AppGlobalVariables::$system_id ?>"},
        type:"post",
        dataType:"json",
        async:false,
        success:function(data){
            /** 是否正常请求 */
            if(data["result"] != 0)
            {
                console.warn("请求失败...！");
                return;
            }
            
            //console.log(data); //在console页面打印数据 
        }
    });
}
</script>