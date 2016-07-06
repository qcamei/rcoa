<?php

namespace frontend\modules\teamwork;

use common\models\teamwork\CourseSummary;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TeamworkTool{
    /**
     * 获取一周时间
     * @param type $course_id
     * @param type $date
     */
    public function getWeek($course_id, $date)
    {
        //$date = date('Y-m-d');  //当前日期
        $first = 1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $w = date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $now_start = date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $now_end = date('Y-m-d',strtotime("$now_start +6 days"));  //本周结束日期
        //$last_start=date('Y-m-d',strtotime("$now_start - 7 days"));  //上周开始日期
        //$last_end=date('Y-m-d',strtotime("$now_start - 1 days"));  //上周结束日期
        $result = CourseSummary::find()->where(['course_id' => $course_id])
                ->andWhere('create_time >="'. $now_start.'"')
                ->andWhere('create_time <="'. $now_end.'"')
                ->one();
        return $result;
    }
}