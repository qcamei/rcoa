<?php

use common\models\scene\SceneBook;
use yii\helpers\Html;

?>

<table class="table table-striped table-bordered table-month">
    
    <thead>
        <tr>
            <th>日</th>
            <th>一</th>
            <th>二</th>
            <th>三</th>
            <th>四</th>
            <th>五</th>
            <th>六</th>
        </tr>
    </thead>
    
    <tbody>
        <?php  
            $allModels = [];
            $timeIndexMap = ['上', '下', '晚'];
            //重组预约数据模型
            foreach ($dataProvider->allModels as $model){
                $allModels[$model->date][] = $model;
            }
            //分割日期
            $dateArray = explode('-', date('Y-m-d', strtotime(array_keys($allModels)[0])));
            //当前月第一个星期日是几号
            $firstSunday = date('Y-m-d', strtotime("first sunday of $dateArray[0]-$dateArray[1]"));
            //当前月最后一个星期日是几号
            $lastSunday = date('Y-m-d', strtotime("last sunday of $dateArray[0]-$dateArray[1]"));
            //当前月第一个星期日是往前7天的日期
            $dateStart = date('Y-m-d', strtotime(date('Y-m-d', strtotime("$firstSunday -".(7).' days'))));
            //当前月最后一个星期日是往后7天的日期
            $dateEnd = date('Y-m-d', strtotime(date('Y-m-d', strtotime("$lastSunday +".(7).' days'))));
            //当前月第一个星期日是往前7天和当前月最后一个星期日是往后6天之间的天数
            $dayNum = (strtotime($dateEnd) - strtotime($dateStart)) / 86400;
            //date('d')+1 明天预约时间
            $dayTomorrow = date('Y-m-d H:i:s',strtotime("+1 days"));
            //30天后预约时间
            $dayEnd = date('Y-m-d H:i:s',strtotime("+31 days"));
            for ($i = 0; $i < ceil($dayNum / 7); $i++){
                echo '<tr>';
                for ($d = 0; $d < 7; $d++) {
                    $nowDay = 7 * $i + $d + 0;
                    $date = date('Y-m-d', strtotime($dateStart.'+'.$nowDay.'days'));
                    echo '<td>';
                    echo "<span class=\"days\">".preg_replace('/^0+/','',date('d', strtotime($date)))."</span>";
                    echo "<div class=\"btn-group\">";
                    if(isset($allModels[$date])){
                        for ($index = 0; $index < 3; $index++){
                            //预约时间
                            $bookTime = date('Y-m-d H:i:s', strtotime($allModels[$date][$index]->date.SceneBook::$startTimeIndexMap[$index]));
                            $isDisable = 
                            $isNew = $allModels[$date][$index]->getIsNew();                        //新建任务
                            $isValid = $allModels[$date][$index]->getIsValid();                    //非新建及锁定任务
                            $isBooking = $allModels[$date][$index]->getIsBooking();                //是否预约中
                            $isAssign = $allModels[$date][$index]->getIsAssign();                  //是否在【待指派】任务
                            $isStausShootIng = $allModels[$date][$index]->getIsStausShootIng();    //是否在【待评价】任务
                            $isMe = $allModels[$date][$index]->booker_id == Yii::$app->user->id;   //该预约是否为自己预约
                            $isTransfer = $allModels[$date][$index]->is_transfer;                  //该预约是否为转让预约
                            //判断30天内的预约时段
                            if($dayTomorrow < $bookTime && $bookTime < $dayEnd){
                                $buttonName = $isNew  ? '<i class="fa fa-video-camera"></i>&nbsp;预约' : (!$isValid ? '预约中' : ($isTransfer ? '<i class="fa fa-refresh"></i>&nbsp;转让' : ($isAssign ? $allModels[$date][$index]->getStatusName() : $allModels[$date][$index]->getStatusName())));
                                $buttonClass = $isNew ? 'btn-primary' : (!$isValid ? 'btn-primary disabled' : ($isTransfer ? 'btn-primary' : ($isAssign ? 'btn-info' : 'btn-default')));
                            }else{
                                $buttonName = !$isNew ? ($isTransfer ? '<i class="fa fa-refresh"></i>&nbsp;转让' : $allModels[$date][$index]->getStatusName()) : '<i class="fa fa-ban"></i>&nbsp;禁用';
                                $buttonClass = !$isNew ? ($isTransfer ? 'btn-primary' : 'btn-default') : 'btn-default disabled';
                            }
                            $url = $isNew ? 
                                ['create', 'id' => $allModels[$date][$index]->id, 'site_id' => $allModels[$date][$index]->site_id, 
                                    'date' => $allModels[$date][$index]->date, 'time_index' => $allModels[$date][$index]->time_index, 
                                    'date_switch' => $allModels[$date][$index]->date_switch] : ['view', 'id' => $allModels[$date][$index]->id];
                            
                            echo "<p><span class=\"month_time_index\">{$timeIndexMap[$index]}</span>";
                            echo  Html::a('<span class="'.($isMe ? 'isMe' : '').'"></span>'.$buttonName, $url, ['class' => "btn $buttonClass btn-sm btn-len"]);
                            echo "</p>";
                        }
                    }
                    echo "</div>";
                    echo '</td>';
                }
                echo '</tr>';
            }
        ?>
    </tbody>
    
</table>