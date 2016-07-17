<?php

use common\models\System;
use common\wskeee\job\JobManager;
use common\wskeee\job\models\Job;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* @var $jobManager JobManager */
$jobManager = Yii::$app->get('jobManager');
$notification = $jobManager->getNotification(Yii::$app->user->id);
$system = System::find()->with('jobs')->all();

?>
<span class="badge badge-important"><?php echo count($notification)?></span>
<ul class="dropdown-menu extended tasks-bar">
    <li>
        <p id="text">你共 <?php echo count($notification)?> 个任务正在进行中</p>
    </li>
    <?php 
        foreach ($system as $value) {
            $haveReadNotice = $jobManager->getHaveReadNotice(ArrayHelper::getColumn($notification, 'job_id'), ['system_id' => $value->id]);
            if(empty($haveReadNotice)) continue;
            echo '<li>';
            echo '<p>【'.$value->name.'】</p>';    
            echo '</li>';
            foreach ($haveReadNotice as $values) {
                echo '<li>';
                echo Html::a('<div class="task-info">'
                        . '<div class="desc">'
                        . '<span>【'.$values->status.'】</span>'
                        .$values->subject.'</div>'
                        . '<div class="percent">'.$values->progress.'%</div>'
                        . '</div><div class="progress progress-striped active no-margin-bot">'
                        . '<div class="bar" style="width:'.$values->progress.'%;"></div></div>', [$values->link]);
                echo '</li>';
            }
        }
    ?>
    
    
   
    
</ul>
