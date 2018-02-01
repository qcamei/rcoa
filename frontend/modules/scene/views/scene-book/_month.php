<?php

use common\models\Holiday;
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
            $holidayColourMap = [1 => 'red', 2 => 'yellow', 3 => 'green'];
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
                    if($date != date('Y-m-d', time())){
                        echo '<td>';
                    }else{
                        echo '<td style="background-color: rgba(30, 181, 176, 0.10196078431372549)">';
                    }
                        echo '<div>';
                            if(isset($holidays[$date])){
                                $first = reset($holidays[$date]);
                                $content = '';
                                foreach ($holidays[$date] as $holiday) 
                                    $content .= "<p>{$holiday['name']}(".Holiday::TYPE_NAME_MAP[$holiday['type']].")</p>";
                                echo "<a class=\"holiday img-circle {$holidayColourMap[$first['type']]}\" role=\"button\" data-content=\"{$content}\">".Holiday::TYPE_NAME_MAP[$first['type']]."</a>";
                            }
                            if(!isset($allModels[$date])){
                                echo "<span class=\"days\" style=\"color:#ddd;\">".preg_replace('/^0+/','',date('d', strtotime($date)))."</span>";
                            }else if($date != date('Y-m-d', time())){
                                echo "<span class=\"days\">".preg_replace('/^0+/','',date('d', strtotime($date)))."</span>";
                            }else{
                                echo "<span class=\"days img-rounded now\">".preg_replace('/^0+/','',date('d', strtotime($date)))."</span>";
                            }
                            echo "<div class=\"btn-group\">";
                            if(isset($allModels[$date])){
                                for ($index = 0; $index < 3; $index++){
                                    //预约时间
                                    $bookTime = date('Y-m-d H:i:s', strtotime($allModels[$date][$index]->date.SceneBook::$startTimeIndexMap[$index]));
                                    $statusName = $allModels[$date][$index]->getStatusName();              //状态名称
                                    $isNew = $allModels[$date][$index]->getIsNew();                        //新建任务
                                    $isValid = $allModels[$date][$index]->getIsValid();                    //非新建及锁定任务
                                    $isBooking = $allModels[$date][$index]->getIsBooking();                //是否预约中
                                    $isAssign = $allModels[$date][$index]->getIsAssign();                  //是否在【待指派】任务
                                    $isStausShootIng = $allModels[$date][$index]->getIsStausShootIng();    //是否在【待评价】任务
                                    $isAppraise = $allModels[$date][$index]->getIsAppraise();              //是否在【评价中】任务
                                    $isBreakPromise = $allModels[$date][$index]->getIsStatusBreakPromise();//是否在【已失约】任务          
                                    $isMe = $allModels[$date][$index]->booker_id == Yii::$app->user->id;   //该预约是否为自己预约
                                    $isTransfer = $allModels[$date][$index]->is_transfer;                  //该预约是否为转让预约
                                    $isPublishSite = $allModels[$date][$index]->sceneSite->is_publish;     //是否发布该场地
                                    //场次是否禁用
                                    $isDisable = isset($siteManage[$date][$index]) && $siteManage[$date][$index];

                                    //判断30天内的预约时段
                                    if(($dayTomorrow < $bookTime && $bookTime < $dayEnd) && $isPublishSite){
                                        $buttonName = $isDisable ? '<i class="fa fa-ban"></i>　禁' : ($isNew  ? '预　约' : (!$isValid ? '预约中' : ($isTransfer && $bookTime > date('Y-m-d H:i:s', time()) ? '<i class="fa fa-refresh"></i>转让' : ($isAssign ? $allModels[$date][$index]->getStatusName() : $allModels[$date][$index]->getStatusName()))));
                                        $buttonClass = $isDisable ? 'btn-default disabled' : ($isNew ? 'btn-primary' : (!$isValid ? 'btn-primary disabled' : ($isTransfer && $bookTime > date('Y-m-d H:i:s', time()) ? 'btn-primary' : ($isAssign ? 'btn-success' : ($isStausShootIng || $isAppraise ? 'btn-info' : ($isBreakPromise ? 'btn-danger' : 'btn-default'))))));
                                    }else{
                                        $buttonName = !$isNew ?  ($isTransfer && $bookTime > date('Y-m-d H:i:s', time()) ? '<i class="fa fa-refresh"></i>转让' : $statusName) : '<i class="fa fa-ban"></i>　禁';
                                        $buttonClass = !$isNew ?  ($isTransfer && $bookTime > date('Y-m-d H:i:s', time()) ? 'btn-primary' : ($isBreakPromise ? 'btn-danger' : ($isStausShootIng || $isAppraise ? 'btn-info' : 'btn-default'))) : 'btn-default disabled';
                                    }
                                    $url = $isNew ? 
                                        ['create', 'id' => $allModels[$date][$index]->id, 'site_id' => $allModels[$date][$index]->site_id, 
                                            'date' => $allModels[$date][$index]->date, 'time_index' => $allModels[$date][$index]->time_index, 
                                            'date_switch' => $allModels[$date][$index]->date_switch] : ['view', 'id' => $allModels[$date][$index]->id];

                                    echo "<p><span class=\"month_time_index hidden-xs\">".SceneBook::$timeIndexMap[$index]."</span>";
                                    echo  Html::a('<span class="'.($isMe ? 'isMe' : '').'"></span>'.$buttonName, $url, ['class' => "btn $buttonClass btn-sm btn-len", 'target' => $isNew ? : '_blank']);
                                    echo "</p>"; 
                                }
                            }
                            echo "</div>";
                        echo '</div>';
                    echo '</td>';
                }
                echo '</tr>';
            }
        ?>
    </tbody>
    
</table>