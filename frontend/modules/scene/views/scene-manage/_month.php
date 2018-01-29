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
            $timeIndexMap = ['上', '下', '晚'];
            $holidayColourMap = [1 => 'red', 2 => 'orange', 3 => 'green'];
            //重组禁用数据模型
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
            //date('d')+1 明天禁用时间
            $dayTomorrow = date('Y-m-d H:i:s',strtotime("+1 days"));
            
            for ($i = 0; $i < ceil($dayNum / 7); $i++){
                echo '<tr>';
                for ($d = 0; $d < 7; $d++) {
                    $nowDay = 7 * $i + $d + 0;
                    $date = date('Y-m-d', strtotime($dateStart.'+'.$nowDay.'days'));
                    echo '<td>';
                    if(isset($holidays[$date])){
                        $first = reset($holidays[$date]);
                        $content = '';
                        foreach ($holidays[$date] as $holiday) 
                            $content .= "<p>{$holiday['name']}(".Holiday::TYPE_NAME_MAP[$holiday['type']].")</p>";
                        echo "<a class=\"holiday img-circle {$holidayColourMap[$first['type']]}\" role=\"button\" data-content=\"{$content}\">".Holiday::TYPE_NAME_MAP[$first['type']]."</a>";
                    }
                    if($date != date('Y-m-d', time())){
                        echo "<span class=\"days\">".preg_replace('/^0+/','',date('d', strtotime($date)))."</span>";
                    }else{
                        echo "<span class=\"days img-rounded now\">".preg_replace('/^0+/','',date('d', strtotime($date)))."</span>";
                    }
                    echo "<div class=\"btn-group\">";
                    if(isset($allModels[$date])){
                        for ($index = 0; $index < 3; $index++){
                            //禁用时间
                            $disableTime = date('Y-m-d H:i:s', strtotime($allModels[$date][$index]->date.SceneBook::$startTimeIndexMap[$index]));
                            $isDisable = $allModels[$date][$index]->is_disable;                     //是否已禁用
                            $isBook = isset($books[$date][$index]) && $books[$date][$index] != null;//是否已预约                   
                            //判断可禁用日期
                            if($dayTomorrow < $disableTime){
                                $buttonName =  $isBook ? '已约' : (!$isDisable ? '启用' : '<i class="fa fa-ban"></i>禁');
                                $buttonClass = $isBook ? 'btn-default disabled' : (!$isDisable ? 'btn-info' : 'btn-default');
                            } else {
                                $buttonName = $isBook ? '已约' : (!$isDisable ? '启用' : '<i class="fa fa-ban"></i>禁');
                                $buttonClass = $isBook ? 'btn-default disabled' : (!$isDisable ? 'btn-default disabled' : 'btn-default disabled');
                            }
                            $url = !$isDisable ? 
                                ['site-disable', 'site_id' => $allModels[$date][$index]->site_id, 
                                    'date' => $allModels[$date][$index]->date, 'time_index' => $allModels[$date][$index]->time_index] : 
                                ['site-enable', 'site_id' => $allModels[$date][$index]->site_id, 
                                    'date' => $allModels[$date][$index]->date, 'time_index' => $allModels[$date][$index]->time_index];
                            
                            echo "<p><span class=\"month_time_index hidden-xs\">{$timeIndexMap[$index]}</span>";
                            echo  Html::a($buttonName, $url, ['class' => "btn $buttonClass btn-sm btn-len"]);
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