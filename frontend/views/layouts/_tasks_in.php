<?php

use common\wskeee\job\models\Job;
use yii\helpers\Html;
use yii\web\View;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$job = new Job();
$jobManager = Yii::$app->get('jobManager');
$haveReadNotice = $jobManager->getNotification(Yii::$app->user->id);
?>
<span class="badge badge-important"><?php echo count($haveReadNotice)?></span>
<ul class="dropdown-menu extended tasks-bar">
    <li>
        <p id="text">你共 <?php echo count($haveReadNotice)?> 个任务正在进行中</p>
    </li>
    <li>
        <p>【拍摄】</p>
    </li>
    
     <?php 
        foreach ($haveReadNotice as $key=>$value) {
            if($key > 1 ||  $value->system_id != 2) continue;
            echo '<li>';
            echo Html::a('<div class="task-info">'
                    . '<div class="desc">'
                    . '<span>【'.$value->status.'】</span>'
                    .$value->subject.'</div>'
                    . '<div class="percent">'.$value->progress.'%</div>'
                    . '</div><div class="progress progress-striped active no-margin-bot">'
                    . '<div class="bar" style="width:'.$value->progress.'%;"></div></div>', [$value->link]);
            echo '</li>';
        }
        
    ?>
    
    
    <li>
        <p>【多媒体制作】</p>
    </li>
   
     <?php 
        foreach ($haveReadNotice as $key=>$value) {
            if($key > 2 ||  $value->system_id != 3) continue;
            echo '<li>';
            echo Html::a('<div class="task-info">'
                    . '<div class="desc">'
                    . '<span>【'.$value->status.'】</span>'
                    .$value->subject.'</div>'
                    . '<div class="percent">'.$value->progress.'%</div>'
                    . '</div><div class="progress progress-striped active no-margin-bot">'
                    . '<div class="bar" style="width:'.$value->progress.'%;"></div></div>', [$value->link]);
            echo '</li>';
        }
        
    ?>
    
</ul>
