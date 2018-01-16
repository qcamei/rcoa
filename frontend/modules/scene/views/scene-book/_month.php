<?php

use common\models\scene\SceneBook;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
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
//            $startWeek = 0;
//            $mday = 1;
//            $date = isset($dataProvider->allModels[0]) ? $dataProvider->allModels[0]->date : date('Y-m-d');
//            $start = date('w', strtotime("first monday of $this->date_start"));           //当月从星期几天始
//            $end = cal_days_in_month(CAL_GREGORIAN, $dateArray[1], $dateArray[0]);        //当月的天数     
//            for($i=0; $i < ceil((intval($start) + $end) / 7); $i++){
//                echo '<tr>';
//                    for ($d = 0; $d < 7; $d++) {
//                        $nowday = 7 * $i + $d + $startWeek;
//                        if($nowday >= $start && $mday < count($dataProvider->allModels)){
//                            echo '<td>'.date('d', strtotime($dataProvider->allModels[$nowday-1]->date . '-' . $mday)) . '</td>';
//                            $mday++;
//                        }else{
//                            echo '<td> </td>';
//                        }
//                    }
//                echo '</tr>';
//            }
        ?>
    </tbody>
    
</table>